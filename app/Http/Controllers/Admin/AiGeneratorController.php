<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class AiGeneratorController extends Controller
{
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'nullable|numeric',
            'keywords' => 'nullable|string|max:500',
        ]);

        $category = $validated['category_id'] ? Category::find($validated['category_id']) : null;
        $catName = $category?->name ?? 'equipement';
        $keywords = $validated['keywords'] ?? '';
        $name = $validated['name'] ?: ($keywords ? explode(',', $keywords)[0] : 'Article');
        $price = $validated['price'] ?? 0;

        $features = $this->generateFeatures($catName, $keywords);
        $specs = $this->generateSpecs($catName);
        $description = $this->generateDescription($name, $catName, $features);
        $metaTitle = $this->generateMetaTitle($name);
        $metaDescription = $this->generateMetaDescription($name, $catName);

        return response()->json([
            'description' => $description,
            'specifications' => $specs,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
        ]);
    }

    private function generateDescription(string $name, string $category, array $features): string
    {
        $lines = [];
        $lines[] = "<p>Article {$category} — le {$name} est conçu pour répondre aux exigences les plus élevées des cavaliers. Fabriqué à partir de matériaux de qualité supérieure, cet équipement offre des performances, une durabilité et une fiabilité exceptionnelles.</p>";
        $lines[] = "<p>Chaque article {$category} est soumis à des contrôles qualité rigoureux afin de garantir un usage durable et un ajustement parfait. Pour les cavaliers amateurs comme pour les professionnels, vous pouvez avoir confiance en cet équipement.</p>";
        $lines[] = "<p><strong>Points clés :</strong></p><ul>";
        foreach ($features as $feature) {
            $lines[] = "<li>{$feature}</li>";
        }
        $lines[] = "</ul>";
        $lines[] = "<p>Compatibilité étendue — vérifiez l'ajustement avec notre équipe ou contactez notre service client pour toute question.</p>";

        return implode("\n", $lines);
    }

    private function generateFeatures(string $category, string $keywords): array
    {
        $base = [
            "Matériaux de qualité supérieure pour une durabilité maximale",
            "Finition soignée, résistante à l'usure",
            "Testé rigoureusement selon des normes strictes",
            "Conçu pour un confort et une sécurité optimale",
            "Couvert par la garantie complète du fabricant",
        ];

        $catFeatures = [
            'selle' => ['Cuir souple et résistant pour un confort durable', 'Arçon renforcé pour une parfaite répartition du poids', 'Finition cousue main par des artisans selliers'],
            'bride' => ['Cuir de vachette tanné végétal', 'Ferrure en acier inoxydable', 'Réglage précis et aisé'],
            'casque' => ['Coque résistante aux chocs', 'Norme CE en vigueur', 'Ventilation optimisée'],
            'protège' => ['Mousse haute densité', 'Fermeture velcro renforcée', 'Lavable en machine'],
        ];

        foreach ($catFeatures as $key => $extra) {
            if (str_contains(strtolower($category), $key)) {
                $base = array_merge($base, $extra);
                break;
            }
        }

        if (!empty($keywords)) {
            $kw = array_map('trim', explode(',', $keywords));
            foreach ($kw as $k) {
                if (!empty($k)) {
                    $base[] = "Optimisé pour {$k}";
                }
            }
        }

        return array_slice($base, 0, 7);
    }

    private function generateSpecs(string $category): string
    {
        return "<ul>
<li>Catégorie : {$category}</li>
<li>État : Neuf</li>
<li>Garantie : 12 mois</li>
<li>Matériaux : matériaux de qualité équestre</li>
<li>Emballage : emballage sellerie d'origine</li>
</ul>";
    }

    private function generateMetaTitle(string $name): string
    {
        $parts = explode(' ', $name);
        $short = implode(' ', array_slice($parts, 0, 5));
        return "{$short} — Sellerie Super Confort | Livraison rapide en France";
    }

    private function generateMetaDescription(string $name, string $category): string
    {
        $parts = explode(' ', $name);
        $short = implode(' ', array_slice($parts, 0, 6));
        return "Achetez {$short} sur Sellerie Super Confort. Article {$category} de qualité. Livraison rapide en France, garantie 12 mois.";
    }
}
