<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get(['slug', 'updated_at']);
        $categories = Category::where('is_active', true)->get(['slug', 'updated_at']);

        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $content .= $this->url(route('home'), '1.0', 'daily');
        $content .= $this->url(route('shop'), '0.9', 'daily');
        $content .= $this->url(route('categories.all'), '0.8', 'weekly');
        $content .= $this->url(route('about'), '0.6', 'monthly');
        $content .= $this->url(route('contact'), '0.6', 'monthly');
        $content .= $this->url(route('delivery'), '0.5', 'monthly');
        $content .= $this->url(route('returns'), '0.5', 'monthly');
        $content .= $this->url(route('privacy'), '0.3', 'yearly');
        $content .= $this->url(route('terms'), '0.3', 'yearly');
        $content .= $this->url(route('warranty'), '0.5', 'monthly');

        foreach ($categories as $cat) {
            $content .= $this->url(url('/shop?category=' . $cat->slug), '0.7', 'weekly', $cat->updated_at);
        }

        foreach ($products as $product) {
            $content .= $this->url(route('product.show', $product->slug), '0.8', 'weekly', $product->updated_at);
        }

        $content .= '</urlset>';

        return Response::make($content, 200, ['Content-Type' => 'application/xml']);
    }

    private function url(string $loc, string $priority = '0.5', string $changefreq = 'monthly', $lastmod = null): string
    {
        $loc = htmlspecialchars($loc, ENT_XML1, 'UTF-8');
        $s = "  <url>\n    <loc>$loc</loc>\n";
        if ($lastmod) {
            $lm = $lastmod instanceof \Carbon\Carbon ? $lastmod->toDateString() : $lastmod;
            $s .= "    <lastmod>$lm</lastmod>\n";
        }
        $s .= "    <changefreq>$changefreq</changefreq>\n";
        $s .= "    <priority>$priority</priority>\n";
        $s .= "  </url>\n";
        return $s;
    }
}
