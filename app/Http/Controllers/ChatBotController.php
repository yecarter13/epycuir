<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\HuggingFaceService;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    public function message(Request $request, HuggingFaceService $hf)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->input('message');
        $history = $request->input('history', []);

        $systemPrompt = "Vous êtes l'assistant de recherche de la sellerie en ligne Sellerie Super Confort. "
            . "Votre seul rôle est d'aider les clients à trouver le matériel équestre disponible sur notre site. "
            . "Vous avez accès à notre catalogue complet — les résultats affichent les meilleures correspondances. "
            . "Référencez uniquement les produits présents dans la liste fournie. "
            . "Si aucun article ne correspond, répondez 'Aucun article trouvé pour votre recherche' et suggérez d'essayer d'autres mots-clés. "
            . "Ne répondez pas aux questions générales et ne parlez que de notre catalogue. "
            . "Soyez concis. Répondez dans la langue du client."
            . "\n\nQuand vous listez des articles, formatez-les toujours en liste à puces avec des tirets, un produit par ligne. "
            . "Exemple :\n"
            . "- Nom du produit — €Prix\n"
            . "- Nom du produit — €Prix\n"
            . "Ne mettez pas plusieurs produits sur la même ligne. Regroupez par catégorie si possible. "
            . "Commencez par une ligne courte comme 'J'ai trouvé X articles correspondants :' puis listez-les.";

        $params = $hf->parseSearchIntent($userMessage);

        $allProducts = $this->searchProducts($params, 100);
        $displayProducts = $allProducts->take(8);

        $context = $allProducts->map(fn($p) => [
            'name' => $p->name,
            'price' => number_format($p->price, 2),
            'brand' => $p->brand ?? 'Generic',
            'category' => $p->category?->name ?? 'General',
        ])->toArray();

        if ($hf->isAvailable()) {
            $contextPrompt = $systemPrompt;
            if (!empty($context)) {
                $totalProducts = count($context);
                $contextPrompt .= "\n\nJ'ai trouvé {$totalProducts} article(s) correspondant(s) dans notre catalogue. Listez-les avec des tirets, un par ligne. Aidez le client à choisir le bon article en citant nom, prix et marque.";
            } else {
                $contextPrompt .= "\n\nAucun article correspondant n'a été trouvé dans notre catalogue. Suggérez au client d'essayer d'autres mots-clés, de vérifier l'orthographe ou de parcourir les catégories. N'affirmez pas que des articles existent s'ils ne sont pas listés.";
            }
            $aiResponse = $hf->chat($contextPrompt, $userMessage, $context);
        } else {
            $aiResponse = '';
        }

        if (!$hf->isAvailable() || $aiResponse === $hf->getLastFallback()) {
            if (!empty($context)) {
                $names = array_slice(array_map(fn($p) => $p['name'], $context), 0, 5);
                $bulletList = '';
                foreach ($names as $n) {
                    $bulletList .= "\n- " . $n;
                }
                $aiResponse = 'J\'ai trouvé ' . count($context) . ' article correspondant' . (count($context) > 1 ? 's' : '') . ':' . $bulletList
                    . "\n\nCliquez sur un article pour plus de détails. "
                    . 'Dites-moi si vous avez besoin d\'autre chose !';
            } else {
                $aiResponse = 'Aucun article trouvé pour "' . $userMessage . '". Essayez d\'autres mots-clés comme "selle de dressage" ou parcourez nos catégories.';
            }
        }

        $productResults = $displayProducts->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'price' => '€' . number_format($p->price, 2),
            'image' => $p->image,
            'brand' => $p->brand,
            'category' => $p->category?->name,
            'url' => route('product.show', $p->slug),
        ]);

        return response()->json([
            'reply' => $aiResponse,
            'products' => $productResults,
            'params' => $params,
        ]);
    }

    public function aiSearch(Request $request, HuggingFaceService $hf)
    {
        $request->validate([
            'q' => 'required|string|max:500',
        ]);

        $query = $request->input('q');
        $params = $hf->parseSearchIntent($query);
        $allProducts = $this->searchProducts($params, 100);
        $products = $allProducts->take(8);

        return response()->json([
            'products' => $products->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => '€' . number_format($p->price, 2),
                'image' => $p->image,
                'brand' => $p->brand,
                'category' => $p->category?->name,
                'url' => route('product.show', $p->slug),
            ]),
            'params' => $params,
        ]);
    }

    protected function searchProducts(array $params, int $limit = 8)
    {
        $query = Product::where('is_active', true)->with('category');

        if (!empty($params['category'])) {
            $category = Category::where('name', 'like', '%' . $params['category'] . '%')->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if (!empty($params['min_price'])) {
            $query->where('price', '>=', (float) $params['min_price']);
        }

        if (!empty($params['max_price'])) {
            $query->where('price', '<=', (float) $params['max_price']);
        }

        if (!empty($params['keywords'])) {
            $keywords = $params['keywords'];
            $terms = preg_split('/[\s,]+/', trim($keywords));
            $terms = array_filter($terms, fn($t) => strlen($t) >= 2);
            $fulltextTerms = [];

            foreach ($terms as $t) {
                $fulltextTerms[] = '+' . preg_replace('/[+\-><\(\)~*\"@]/', '', $t) . '*';
            }

            if (!empty($fulltextTerms)) {
                $boolQuery = implode(' ', $fulltextTerms);
                $query->where(function ($q) use ($boolQuery, $terms) {
                    $q->whereRaw('MATCH(name, sku, description, compatibility, brand) AGAINST(? IN BOOLEAN MODE)', [$boolQuery]);
                    $q->orWhere(function ($sub) use ($terms) {
                        foreach ($terms as $t) {
                            $sub->where(function ($s) use ($t) {
                                $s->where('name', 'like', '%' . $t . '%')
                                  ->orWhere('brand', 'like', '%' . $t . '%')
                                  ->orWhere('compatibility', 'like', '%' . $t . '%');
                            });
                        }
                    });
                });
            }
        }

        return $query->take($limit)->get();
    }
}
