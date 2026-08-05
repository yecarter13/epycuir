<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index()
    {
        $slides = collect(['hero_banner_1', 'hero_banner_2', 'hero_banner_3'])
            ->map(fn($key) => \App\Models\SiteSetting::getValue($key))
            ->filter()
            ->values()
            ->map(fn($url, $i) => (object) [
                'title' => match($i) { 0 => 'L\'élégance à cheval', 1 => 'Selles et sellerie d\'exception', default => 'Équipement équestre made in France' },
                'subtitle' => match($i) { 0 => 'Selles, filets et harnachements sélectionnés avec soin pour le confort de votre cheval', 1 => 'Artisanat français, cuirs nobles et savoir-faire transmis de génération en génération', default => 'Livraison rapide partout en France — conseil personnalisé par nos selliers expérimentés' },
                'cta_primary' => match($i) { 0 => 'Explorer la boutique', default => 'Voir le catalogue' },
                'cta_secondary' => match($i) { 0 => 'Trouver un équipement', 1 => 'En savoir plus', default => 'Livraison' },
                'image' => $url,
                'tag' => match($i) { 0 => 'Qualité artisanale', 1 => 'Cuirs nobles', default => 'Livraison rapide' },
            ]);

        if ($slides->isEmpty()) {
            $slides = collect([
                (object) ['title' => 'L\'élégance à cheval', 'subtitle' => 'Selles et sellerie d\'exception pour cavaliers exigeants', 'cta_primary' => 'Explorer la boutique', 'cta_secondary' => 'Trouver un équipement', 'image' => 'https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=1920&q=80', 'tag' => 'Qualité artisanale'],
            ]);
        }

        $imageMap = [
            'Selles & Accessoires' => 'selle.jpg',
            'Bridons & Mors' => 'bridons.jpg',
            'Protection du cheval' => 'protectionscheval.jpg',
            'Équipement du cavalier' => 'equiepements.jpg',
            'Selles' => 'images/selles.png',
            'Brides & Filets' => 'images/brides.png',
            'Tapis & Protections' => 'images/tapis.png',
            'Harnais d\'attelage' => 'images/harnais.png',
            'Licols & Longes' => 'images/licols.png',
            'Étrilles & Brosses' => 'images/brosses.png',
            'Accessoires d\'écurie' => 'images/ecurie.png',
            'Plombs & Petit matériel' => 'images/default.png',
            'Cloches & Guêtres' => 'images/default.png',
        ];

        $dbCategories = Category::withCount('products')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->filter(fn($c) => $c->products_count > 0)
            ->map(fn($c) => (object) [
                'name' => $c->name,
                'slug' => $c->slug,
                'image' => $c->image ?? $imageMap[$c->name] ?? 'images/default.png',
                'count' => $c->products_count,
            ])
            ->values();

        $categories = $dbCategories->isNotEmpty() ? $dbCategories->take(12) : collect([
            (object) ['name' => 'Selles', 'image' => 'selles.png', 'count' => 0, 'slug' => 'selles'],
        ]);

        $products = Product::where('is_active', true)->inRandomOrder()->take(8)->get();

        $brands = collect(config('brands'))
            ->map(fn($logo, $name) => (object) [
                'name' => $name,
                'initial' => mb_substr($name, 0, 1),
                'image' => $logo,
            ])
            ->values();

        $testimonials = [
            (object) ['name' => 'Camille Dubois', 'location' => 'Lyon', 'avatar' => 'https://i.pravatar.cc/100?u=1', 'rating' => 5, 'text' => 'Une selle de dressage sur mesure d\'une qualité exceptionnelle. Le sellier a pris le temps de me conseiller et l\'ajustement est parfait pour mon cheval. Je recommande vivement.'],
            (object) ['name' => 'Julien Marchand', 'location' => 'Bordeaux', 'avatar' => 'https://i.pravatar.cc/100?u=2', 'rating' => 5, 'text' => 'Service client remarquable. Mon filet était en rupture mais l\'équipe a trouvé la référence chez le fournisseur en 48h. Livraison rapide et emballage soigné.'],
            (object) ['name' => 'Sophie Bernard', 'location' => 'Normandie', 'avatar' => 'https://i.pravatar.cc/100?u=3', 'rating' => 5, 'text' => 'Les tapis de selle en laine mérinos sont superbes et durent des années. Le rapport qualité-prix est imbattable pour du matériel français de cette qualité.'],
            (object) ['name' => 'Antoine Roussel', 'location' => 'Pau', 'avatar' => 'https://i.pravatar.cc/100?u=4', 'rating' => 4, 'text' => 'Depuis trois ans, toute mon écurie s\'équipe chez eux. Matériel robuste, tarifs pro, et conseils toujours pertinents. Nos chevaux nous remercient !'],
            (object) ['name' => 'Élise Fontaine', 'location' => 'Versailles', 'avatar' => 'https://i.pravatar.cc/100?u=5', 'rating' => 5, 'text' => 'J\'ai commandé un licol en cuir avec plaque gravée pour l\'anniversaire de ma jument : une merveille. Le cuir est souple et le fini impeccable.'],
            (object) ['name' => 'Thomas Girard', 'location' => 'Toulouse', 'avatar' => 'https://i.pravatar.cc/100?u=6', 'rating' => 5, 'text' => 'Un attelage complet réparé et adapté en quelques jours par un artisan qui connaît son métier. Rare de trouver pareil savoir-faire aujourd\'hui.'],
        ];

        return view('pages.home', compact('slides', 'categories', 'products', 'brands', 'testimonials'));
    }
}
