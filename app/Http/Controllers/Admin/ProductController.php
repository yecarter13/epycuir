<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $product = null;
        return view('admin.products.form', compact('categories', 'product'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'compatibility' => 'nullable|string|max:500',
            'image' => 'nullable|string|max:500',
            'gallery_images' => 'nullable|json',
            'brand' => 'nullable|string|max:255',
            'is_new' => 'boolean',
            'is_active' => 'boolean',
            'stock_quantity' => 'integer|min:0',
            'rating' => 'numeric|min:0|max:5',
            'review_count' => 'integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $data['is_new'] = $request->boolean('is_new');
        $data['is_active'] = $request->boolean('is_active');
        if (empty($data['sku'])) {
            $data['sku'] = 'AP-' . strtoupper(Str::random(6));
        }

        if ($request->hasFile('image_file')) {
            $name = Str::random(20) . '.' . $request->file('image_file')->extension();
            $request->file('image_file')->move(base_path('public/images/products'), $name);
            $data['image'] = '/images/products/' . $name;
        } elseif (!empty($data['image'])) {
            $data['image'] = $this->normalizeImagePath($data['image']);
        }
        if (!empty($data['gallery_images'])) {
            $data['gallery_images'] = $this->normalizeGalleryJson($data['gallery_images']);
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $storedGallery = json_decode($product->getRawOriginal('gallery_images') ?? '[]', true);
        return view('admin.products.form', compact('product', 'categories', 'storedGallery'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'compatibility' => 'nullable|string|max:500',
            'image' => 'nullable|string|max:500',
            'gallery_images' => 'nullable|json',
            'brand' => 'nullable|string|max:255',
            'is_new' => 'boolean',
            'is_active' => 'boolean',
            'stock_quantity' => 'integer|min:0',
            'rating' => 'numeric|min:0|max:5',
            'review_count' => 'integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $data['is_new'] = $request->boolean('is_new');
        $data['is_active'] = $request->boolean('is_active');
        if (empty($data['sku'])) {
            $data['sku'] = 'AP-' . strtoupper(Str::random(6));
        }

        if ($request->hasFile('image_file')) {
            $name = Str::random(20) . '.' . $request->file('image_file')->extension();
            $request->file('image_file')->move(base_path('public/images/products'), $name);
            $data['image'] = '/images/products/' . $name;
        } elseif (!empty($data['image'])) {
            $data['image'] = $this->normalizeImagePath($data['image']);
        }
        if (!empty($data['gallery_images'])) {
            $data['gallery_images'] = $this->normalizeGalleryJson($data['gallery_images']);
        }

        try {
            $product->update($data);
        } catch (UniqueConstraintViolationException $e) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
            $product->update($data);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    protected function normalizeGalleryJson(string $json): string
    {
        $urls = json_decode($json, true);
        if (!is_array($urls)) return $json;
        $normalized = array_map(fn($url) => $this->normalizeImagePath($url), $urls);
        return json_encode($normalized);
    }

    protected function normalizeImagePath(string $path): string
    {
        $imagesUrl = url('/images/');
        if (str_starts_with($path, $imagesUrl)) {
            return '/' . ltrim(parse_url($path, PHP_URL_PATH), '/');
        }
        $storageUrl = url('/storage/');
        if (str_starts_with($path, $storageUrl)) {
            return '/' . ltrim(parse_url($path, PHP_URL_PATH), '/');
        }
        $proxyPrefix = route('image.proxy', ['url' => '']);
        if (str_starts_with($path, $proxyPrefix)) {
            $parsed = parse_url($path);
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $query);
                if (isset($query['url'])) {
                    return $this->normalizeImagePath($query['url']);
                }
            }
        }
        return $path;
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès.');
    }
}
