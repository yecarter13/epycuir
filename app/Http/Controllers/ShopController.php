<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\HuggingFaceService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function suggest(Request $request)
    {
        $search = $request->input('q');
        if (!$search || strlen($search) < 2) {
            return response()->json([]);
        }

        $terms = preg_split('/[\s,]+/', trim($search));
        $terms = array_filter($terms, fn($t) => strlen($t) >= 2);

        $fulltextTerms = [];
        foreach ($terms as $t) {
            $fulltextTerms[] = '+' . preg_replace('/[+\-><\(\)~*\"@]/', '', $t) . '*';
        }

        $products = collect();
        if (!empty($fulltextTerms)) {
            $boolQuery = implode(' ', $fulltextTerms);
            try {
                $products = Product::where('is_active', true)
                    ->whereRaw('MATCH(name, sku, description, compatibility, brand) AGAINST(? IN BOOLEAN MODE)', [$boolQuery])
                    ->take(8)
                    ->get(['id', 'name', 'slug', 'price', 'image', 'brand']);
            } catch (\Exception $e) {
                // FULLTEXT index may be missing - fall through to LIKE
            }
        }

        if ($products->count() < 8) {
            $existingIds = $products->pluck('id')->toArray();
            $like = Product::where('is_active', true)
                ->where(function ($q) use ($terms) {
                    foreach ($terms as $t) {
                        $q->where(function ($s) use ($t) {
                            $s->where('name', 'like', '%' . $t . '%')
                              ->orWhere('brand', 'like', '%' . $t . '%')
                              ->orWhere('compatibility', 'like', '%' . $t . '%');
                        });
                    }
                });
            if ($existingIds) {
                $like->whereNotIn('id', $existingIds);
            }
            $likeProducts = $like->take(8 - $products->count())
                ->get(['id', 'name', 'slug', 'price', 'image', 'brand']);
            $products = $products->concat($likeProducts);
        }

        return response()->json([
            'products' => $products->map(fn($p) => [
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => '€' . number_format($p->price, 2),
                'image' => $p->image,
                'brand' => $p->brand,
            ]),
        ]);
    }

    public function index(Request $request, HuggingFaceService $hf)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $query = Product::where('is_active', true)->with('category');

        $currentCategory = null;
        if ($request->filled('category')) {
            $currentCategory = Category::where('slug', $request->category)->first();
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $params = $hf->parseSearchIntent($s);
            $keywords = $params['keywords'] ?? $s;

            if (!empty($params['min_price'])) {
                $query->where('price', '>=', (float) $params['min_price']);
            }
            if (!empty($params['max_price'])) {
                $query->where('price', '<=', (float) $params['max_price']);
            }

            $terms = preg_split('/[\s,]+/', trim($keywords));
            $terms = array_filter($terms, fn($t) => strlen($t) >= 2);
            $query->where(function ($q) use ($terms) {
                $fulltextTerms = [];
                $likeTerms = [];
                foreach ($terms as $t) {
                    $fulltextTerms[] = '+' . preg_replace('/[+\-><\(\)~*\"@]/', '', $t) . '*';
                    $likeTerms[] = $t;
                }
                if ($fulltextTerms) {
                    $booleanQuery = implode(' ', $fulltextTerms);
                    $q->whereRaw('MATCH(name, sku, description, compatibility, brand) AGAINST(? IN BOOLEAN MODE)', [$booleanQuery]);
                }
                if ($likeTerms) {
                    $q->orWhere(function ($sub) use ($likeTerms) {
                        foreach ($likeTerms as $t) {
                            $sub->where(function ($s) use ($t) {
                                $s->where('name', 'like', '%' . $t . '%')
                                  ->orWhere('brand', 'like', '%' . $t . '%')
                                  ->orWhere('compatibility', 'like', '%' . $t . '%')
                                  ->orWhere('sku', 'like', '%' . $t . '%');
                            });
                        }
                    });
                }
            });
            $query->orderByRaw("CASE WHEN name LIKE ? THEN 0 ELSE 1 END", ['%' . $s . '%']);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->input('sort', 'popularity');

        if ($sort === 'random') {
            $query->inRandomOrder();
        } else {
            $query->orderBy(match($sort) {
                'price_asc' => 'price', 'price_desc' => 'price',
                'newest' => 'created_at', 'rating' => 'rating',
                default => 'review_count',
            }, in_array($sort, ['price_asc', 'newest']) ? 'asc' : 'desc');
        }

        $total = $query->count();
        $perPage = 20;
        $products = $query->paginate($perPage)->withQueryString();

        $categoryTitle = null;
        $metaDescription = null;
        if ($currentCategory) {
            $categoryTitle = $currentCategory->name . ' — Sellerie Super Confort';
            $metaDescription = "Découvrez notre gamme de {$currentCategory->name} chez Sellerie Super Confort. Matériel équestre de qualité, livraison rapide en France.";
        }

        return view('pages.shop', compact('categories', 'products', 'total', 'currentCategory', 'categoryTitle', 'metaDescription'));
    }

    public function categories()
    {
        $imageMap = [
            'Selles & Accessoires' => 'selle.jpg',
            'Bridons & Mors' => 'bridons.jpg',
            'Protection du cheval' => 'protectionscheval.jpg',
            'Équipement du cavalier' => 'equiepements.jpg',
            'Étrivières et Étriers' => 'images/default.png',
            'autres' => 'images/default.png',
            'Uncategorized' => 'images/default.png',
        ];

        $categories = Category::withCount('products')
            ->where('is_active', true)
            ->orderBy('products_count', 'desc')
            ->get()
            ->filter(fn($c) => $c->products_count > 0)
            ->map(fn($c) => (object) [
                'name' => $c->name,
                'slug' => $c->slug,
                'image' => $c->image ?? $imageMap[$c->name] ?? 'images/default.png',
                'count' => $c->products_count,
            ])
            ->values();

        return view('pages.categories-index', compact('categories'));
    }
}
