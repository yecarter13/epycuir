<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@scelle.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
                'is_admin' => true,
            ]
        );

        $categories = [
            ['name' => 'Selles', 'slug' => 'selles', 'description' => 'Selles de dressage, d\'obstacle, mixtes et de randonnée.', 'sort_order' => 1],
            ['name' => 'Brides & Filets', 'slug' => 'brides-filets', 'description' => 'Brides complètes, filets et embouchures.', 'sort_order' => 2],
            ['name' => 'Tapis & Protections', 'slug' => 'tapis-protections', 'description' => 'Tapis de selle, amortisseurs, guêtres et cloches.', 'sort_order' => 3],
            ['name' => 'Licols & Longes', 'slug' => 'licols-longes', 'description' => 'Licols en cuir ou nylon, longes et cordelettes.', 'sort_order' => 4],
            ['name' => 'Harnais d\'attelage', 'slug' => 'harnais-attelage', 'description' => 'Harnais d\'attelage et d\'extérieur, mors de meneur.', 'sort_order' => 5],
            ['name' => 'Étrilles & Brosses', 'slug' => 'etrilles-brosses', 'description' => 'Soins du cheval : étrilles, brosses et cure-pieds.', 'sort_order' => 6],
            ['name' => 'Équipement du cavalier', 'slug' => 'equipement-cavalier', 'description' => 'Bottes, santiags, bombes, gants et vestes.', 'sort_order' => 7],
            ['name' => 'Accessoires d\'écurie', 'slug' => 'accessoires-ecurie', 'description' => 'Seaux, filets à foin, râteliers et petit matériel.', 'sort_order' => 8],
        ];

        foreach ($categories as $c) {
            Category::firstOrCreate(['slug' => $c['slug']], $c);
        }

        $img1 = 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=600&q=80';
        $img2 = 'https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=600&q=80';
        $img3 = 'https://images.unsplash.com/photo-1518291344630-4857135fb581?w=600&q=80';
        $img4 = 'https://images.unsplash.com/photo-1548802673-380ab8ebc7b7?w=600&q=80';

        $products = [
            [
                'name' => 'Selle de dressage CWD Pro — 17"', 'slug' => 'selle-dressage-cwd-pro-17', 'category_id' => 1,
                'sku' => 'SCL-SEL-001', 'price' => 2890.00, 'old_price' => 3200.00,
                'compatibility' => 'Chevaux de dressage — garrot moyen', 'image' => $img1,
                'brand' => 'CWD', 'is_new' => true, 'stock_quantity' => 4, 'rating' => 4.9, 'review_count' => 27,
                'description' => '<p>Selle de dressage CWD Pro fabriquée sur mesure pour un confort optimal du cheval et du cavalier. Arçon ajustable, cuir grainé pleine fleur, panneaux rembourrés au latex.</p><ul><li>Arçon ajustable et vérifié gratuitement la première année</li><li>Cuir pleine fleur souple et durable</li><li>Panneaux en latex pour une répartition homogène de la pression</li><li>Quartiers réglables pour s\'adapter à la morphologie du cavalier</li><li>Livrée avec sa surfaix en coton</li></ul>',
                'specifications' => '<table><tr><th>Taille</th><td>17&nbsp;pouces (autres tailles sur commande)</td></tr><tr><th>Arçon</th><td>BOA ajustable</td></tr><tr><th>Matière</th><td>Cuir de vachette pleine fleur</td></tr><tr><th>Garantie</th><td>24 mois</td></tr></table>',
            ],
            [
                'name' => 'Selle d\'obstacle Devoucoux Diamant 16.5"', 'slug' => 'selle-obstacle-devoucoux-diamant', 'category_id' => 1,
                'sku' => 'SCL-SEL-002', 'price' => 2450.00, 'old_price' => null,
                'compatibility' => 'Chevaux d\'obstacle — dos normal', 'image' => $img2,
                'brand' => 'Devoucoux', 'is_new' => true, 'stock_quantity' => 3, 'rating' => 4.8, 'review_count' => 19,
                'description' => '<p>La selle Devoucoux Diamant associe légèreté et maintien pour le saut d\'obstacles. Ses quartiers avancés et son siège profond offrent une grande liberté au cavalier.</p><ul><li>Construction monobloc ultra-légère</li><li>Siège profond et quartiers avancés</li><li>Panneaux triangulaires équilibrés</li><li>Cuir de première qualité, aspect brillant</li><li>Idéale concours jusqu\'à 1,50 m</li></ul>',
            ],
            [
                'name' => 'Selle mixte Antarès Révolution 17.5"', 'slug' => 'selle-mixte-antares-revolution', 'category_id' => 1,
                'sku' => 'SCL-SEL-003', 'price' => 2100.00, 'old_price' => 2350.00,
                'compatibility' => 'Polyvalence — dressage et obstacles', 'image' => $img3,
                'brand' => 'Antarès', 'is_new' => false, 'stock_quantity' => 6, 'rating' => 4.7, 'review_count' => 31,
                'description' => '<p>Selle mixte Antarès Révolution, le choix idéal pour le cavalier polyvalent. Confort, élégance et polyvalence réunis dans un savoir-faire français authentique.</p><ul><li>Fabriquée à Saumur</li><li>Arçon en bois et inox réglable</li><li>Cuir anisé de pleine fleur</li><li>Panneaux autoporteurs en mousse à mémoire de forme</li></ul>',
            ],
            [
                'name' => 'Filet simple en cuir de vachette — taille cheval', 'slug' => 'filet-simple-cuir-vachette', 'category_id' => 2,
                'sku' => 'SCL-BRD-004', 'price' => 149.00, 'old_price' => null,
                'compatibility' => 'Taille cheval — 4 tailles disponibles', 'image' => $img4,
                'brand' => 'Sellerie Super Confort', 'is_new' => false, 'stock_quantity' => 25, 'rating' => 4.6, 'review_count' => 54,
                'description' => '<p>Filet simple en cuir de vachette tanné végétal, avec mors à aiguille en inox inclus. Cousu main par nos selliers pour un confort parfait.</p><ul><li>Cuir tanné végétal souple</li><li>Coutures renforcées main</li><li>Mors en inox 316 L inclus</li><li>Montants et frontal galonnés</li></ul>',
            ],
            [
                'name' => 'Bride complète avec filet baucher — poney', 'slug' => 'bride-complete-filet-baucher', 'category_id' => 2,
                'sku' => 'SCL-BRD-005', 'price' => 189.00, 'old_price' => 210.00,
                'compatibility' => 'Taille poney (tests en boutique conseillés)', 'image' => $img1,
                'brand' => 'Stübben', 'is_new' => true, 'stock_quantity' => 12, 'rating' => 4.8, 'review_count' => 22,
                'description' => '<p>Bride complète Stübben avec filet baucher simple et têtière rembourrée. Finition impeccable et cuir durable qui durera des années.</p><ul><li>Cuir anglais souple et résistant</li><li>Têtière anatomique rembourrée</li><li>Mors baucher inox fourni</li><li>Rivets inox anti-corrosion</li></ul>',
            ],
            [
                'name' => 'Tapis de selle laine mérinos 3 cm', 'slug' => 'tapis-selle-laine-merinos', 'category_id' => 3,
                'sku' => 'SCL-TAP-006', 'price' => 59.90, 'old_price' => 69.90,
                'compatibility' => 'Toutes selles — bords roulottés', 'image' => $img2,
                'brand' => 'LeMieux', 'is_new' => false, 'stock_quantity' => 60, 'rating' => 4.7, 'review_count' => 88,
                'description' => '<p>Tapis de selle en laine mérinos pure de 3 cm d\'épaisseur. Absorbe la transpiration et répartit la pression pour protéger le dos du cheval.</p><ul><li>100% laine mérinos naturelle</li><li>Bords roulottés anti-friction</li><li>Passants pour surfaix</li><li>Lavage en machine cycle laine</li></ul>',
            ],
            [
                'name' => 'Guêtres de travail en néoprène — lot de 4', 'slug' => 'guetres-travail-neoprene', 'category_id' => 3,
                'sku' => 'SCL-TAP-007', 'price' => 79.00, 'old_price' => null,
                'compatibility' => 'Taille M/L — antérieurs et postérieurs', 'image' => $img3,
                'brand' => 'Equipe', 'is_new' => false, 'stock_quantity' => 34, 'rating' => 4.5, 'review_count' => 41,
                'description' => '<p>Guêtres de travail en néoprène respirant avec renforts en TPU. Protègent efficacement les membres pendant le travail et les transports.</p><ul><li>Néoprène double densité</li><li>Fermetures à boucles sécurisées</li><li>Respirantes et faciles à nettoyer</li><li>Lot de 4 guêtres</li></ul>',
            ],
            [
                'name' => 'Licol en cuir avec plaque gravée', 'slug' => 'licol-cuir-plaque-gravee', 'category_id' => 4,
                'sku' => 'SCL-LIC-008', 'price' => 89.00, 'old_price' => null,
                'compatibility' => 'Taille cheval standard', 'image' => $img4,
                'brand' => 'Sellerie Super Confort', 'is_new' => true, 'stock_quantity' => 18, 'rating' => 4.9, 'review_count' => 63,
                'description' => '<p>Licol en cuir pleine fleur avec plaque en laiton gravée à votre nom ou au nom de votre cheval. Un cadeau élégant et durable.</p><ul><li>Cuir pleine fleur tanné végétal</li><li>Plaque en laiton gravée (texte de 20 caractères)</li><li>Fermoir et anneaux en laiton massif</li><li>Livré dans sa boîte cadeau</li></ul>',
            ],
            [
                'name' => 'Longe de travail en coton tressé 7 m', 'slug' => 'longe-coton-tresse', 'category_id' => 4,
                'sku' => 'SCL-LIC-009', 'price' => 34.90, 'old_price' => null,
                'compatibility' => 'Longueur 7 m — mousqueton cuivre', 'image' => $img1,
                'brand' => 'Norton', 'is_new' => false, 'stock_quantity' => 45, 'rating' => 4.6, 'review_count' => 37,
                'description' => '<p>Longe de travail en coton tressé de haute qualité, souple et confortable pour la main. Mousqueton en cuivre anti-oxydation.</p><ul><li>Coton tressé 3 brins</li><li>Mousqueton tourniquet en cuivre</li><li>7 mètres de long</li><li>Entretien facile</li></ul>',
            ],
            [
                'name' => 'Harnais d\'attelage en cuir avec gourmette', 'slug' => 'harnais-attelage-cuir', 'category_id' => 5,
                'sku' => 'SCL-HAR-010', 'price' => 1890.00, 'old_price' => 2100.00,
                'compatibility' => 'Chevaux de trait et de loisir', 'image' => $img2,
                'brand' => 'Sellerie Super Confort', 'is_new' => false, 'stock_quantity' => 2, 'rating' => 5.0, 'review_count' => 8,
                'description' => '<p>Harnais d\'attelage complet en cuir de qualité supérieure, réalisé par nos selliers. Comprend collier, dossière, brancards, traits et reculement.</p><ul><li>Cuir façonné à chaud</li><li>Pièces de liaison laiton poli</li><li>Doublures en suédine respirante</li><li>Ajusté et réglé sur votre cheval</li></ul>',
            ],
            [
                'name' => 'Étrille en caoutchouc avec poignée ergonomique', 'slug' => 'etrille-caoutchouc-ergonomique', 'category_id' => 6,
                'sku' => 'SCL-SOI-011', 'price' => 19.90, 'old_price' => null,
                'compatibility' => 'Toutes robes', 'image' => $img3,
                'brand' => 'Shires Equestrian', 'is_new' => false, 'stock_quantity' => 80, 'rating' => 4.4, 'review_count' => 96,
                'description' => '<p>Étrille ronde en caoutchouc souple, idéale pour stimuler la circulation et ôter les poils morts. Poignée ergonomique pour un confort optimal.</p><ul><li>Caoutchouc souple et résistant</li><li>Poignée antidérapante</li><li>Dents arrondies douces</li><li>Lavable à l\'eau savonneuse</li></ul>',
            ],
            [
                'name' => 'Bottes d\'équitation cuir — pair', 'slug' => 'bottes-equitation-cuir', 'category_id' => 7,
                'sku' => 'SCL-CAV-012', 'price' => 129.00, 'old_price' => 159.00,
                'compatibility' => 'Pointures 38 à 46 — montage en 4 jours', 'image' => $img4,
                'brand' => 'Uvex', 'is_new' => true, 'stock_quantity' => 15, 'rating' => 4.7, 'review_count' => 49,
                'description' => '<p>Bottes d\'équitation en cuir véritable avec tige renforcée et semelle à crampons. Confortables à la marche comme à cheval.</p><ul><li>Cuir pleine fleur robuste</li><li>Semelle antidérapante</li><li>Reni fort pour la botte et l\'étrier</li><li>Élastique latéral pour un enfilage facile</li></ul>',
            ],
            [
                'name' => 'Bombe d\'équitation homologuée CE — noire', 'slug' => 'bombe-equitation-homologuee', 'category_id' => 7,
                'sku' => 'SCL-CAV-013', 'price' => 89.00, 'old_price' => null,
                'compatibility' => 'Tailles 54 à 61', 'image' => $img1,
                'brand' => 'Charles Owen', 'is_new' => false, 'stock_quantity' => 20, 'rating' => 4.8, 'review_count' => 58,
                'description' => '<p>Bombe d\'équitation Charles Owen homologuée CE et VG1. Coque en ABS renforcée et intérieur lavable amovible pour un confort optimal.</p><ul><li>Norme CE EN 1384 et VG1</li><li>Coque ABS renforcée</li><li>Ventilation intégrée</li><li>Sangle jugulaire rembourrée</li></ul>',
            ],
            [
                'name' => 'Seau à grain 15 L avec anse renforcée', 'slug' => 'seau-grain-15l', 'category_id' => 8,
                'sku' => 'SCL-ECU-014', 'price' => 12.90, 'old_price' => null,
                'compatibility' => 'Écurie et box', 'image' => $img2,
                'brand' => 'Norton', 'is_new' => false, 'stock_quantity' => 120, 'rating' => 4.5, 'review_count' => 130,
                'description' => '<p>Seau à grain de 15 litres en polypropylène alimentaire avec anse renforcée. Parfait pour la distribution des rations quotidiennes.</p><ul><li>Polypropylène sans BPA</li><li>Anse ergonomique renforcée</li><li>Fond antidérapant</li><li>Lavable en machine à 30°C</li></ul>',
            ],
            [
                'name' => 'Cure-pied double — inox', 'slug' => 'cure-pied-double-inox', 'category_id' => 6,
                'sku' => 'SCL-SOI-015', 'price' => 9.90, 'old_price' => null,
                'compatibility' => 'Entretien des pieds', 'image' => $img3,
                'brand' => 'Shires Equestrian', 'is_new' => false, 'stock_quantity' => 90, 'rating' => 4.3, 'review_count' => 74,
                'description' => '<p>Cure-pied double en inox avec manche gainé cuir. Lame classique d\'un côté, brosse de l\'autre pour un nettoyage complet de la sole.</p><ul><li>Inox poli anti-corrosion</li><li>Manche gainé cuir souple</li><li>Lame arrondie sécurisée</li></ul>',
            ],
            [
                'name' => 'Filet à foin à double maille', 'slug' => 'filet-foin-double-maille', 'category_id' => 8,
                'sku' => 'SCL-ECU-016', 'price' => 24.90, 'old_price' => 29.90,
                'compatibility' => 'Box et voyage', 'image' => $img4,
                'brand' => 'Horseware Amigo', 'is_new' => false, 'stock_quantity' => 55, 'rating' => 4.6, 'review_count' => 68,
                'description' => '<p>Filet à foin à double maille qui ralentit la consommation et limite le gaspillage. Corde de suspension renforcée et rabat facile d\'utilisation.</p><ul><li>Double maille (2,5 et 5 cm)</li><li>Polypropylène ultra résistant</li><li>Lien de fermeture rapide</li><li>Capacité environ 10 kg</li></ul>',
            ],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(['sku' => $p['sku']], $p);
        }
    }
}
