<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class WooCommerceImport extends Command
{
    protected $signature = 'products:import-woocommerce
                            {file : Path to WooCommerce CSV export}
                            {--price-multiplier=0.85 : Multiply price by this factor}
                            {--min-price=100 : Skip products below this price}
                            {--download-images : Download images from source}
                            {--auto-description : Auto-generate description when translation unavailable}';

    protected $description = 'Import products from a WooCommerce CSV export (French) with translation';

    protected array $brandAliases = [
        'bmw' => 'BMW', 'volkswagen' => 'Volkswagen', 'vw' => 'Volkswagen',
        'mercedes' => 'Mercedes-Benz', 'renault' => 'Renault',
        'peugeot' => 'Peugeot', 'citroën' => 'Citroën',
        'mitsubishi' => 'Mitsubishi', 'subaru' => 'Subaru',
        'alfa' => 'Alfa Romeo', 'fiat' => 'Fiat',
        'opel' => 'Opel', 'mini' => 'Mini',
        'audi' => 'Audi', 'seat' => 'Seat',
        'volkswagen' => 'Volkswagen', 'ford' => 'Ford',
        'porsche' => 'Porsche', 'skoda' => 'Skoda',
        'dacia' => 'Dacia', 'toyota' => 'Toyota',
        'honda' => 'Honda', 'nissan' => 'Nissan',
        'mazda' => 'Mazda', 'hyundai' => 'Hyundai',
        'kia' => 'Kia', 'volvo' => 'Volvo',
        'jaguar' => 'Jaguar', 'lamborghini' => 'Lamborghini',
        'ferrari' => 'Ferrari', 'maserati' => 'Maserati',
        'bentley' => 'Bentley', 'rolls' => 'Rolls-Royce',
        'suzuki' => 'Suzuki', 'smart' => 'Smart',
    ];

    protected array $frToEn = [
        'collecteur' => 'Manifold',
        'admission' => 'Intake', 'd\'admission' => 'Intake',
        'papillon' => 'Throttle Body', 'papillons' => 'Throttle Bodies',
        'plenum' => 'Plenum', 'injection' => 'Injection',
        'filtre' => 'Filter', 'injecteurs' => 'Injectors',
        'moteur' => 'Engine', 'moteurs' => 'Engines',
        'complet' => 'Complete', 'avec' => 'With',
        'phares' => 'Headlights', 'phare' => 'Headlight',
        'antibrouillards' => 'Fog Lights',
        'avant' => 'Front', 'arrière' => 'Rear',
        'kit' => 'Kit', 'jantes' => 'Rims', 'jante' => 'Rim',
        'roues' => 'Wheels', 'roue' => 'Wheel',
        'pneu' => 'Tire', 'pneus' => 'Tires',
        'sièges' => 'Seats', 'siège' => 'Seat', 'sieges' => 'Seats', 'siege' => 'Seat',
        'volant' => 'Steering Wheel', 'volants' => 'Steering Wheels',
        'échappement' => 'Exhaust', 'echappement' => 'Exhaust', 'échappements' => 'Exhausts', 'echappements' => 'Exhausts',
        'frein' => 'Brake', 'freins' => 'Brakes', 'freinage' => 'Braking',
        'suspension' => 'Suspension',
        'direction' => 'Steering',
        'carrosserie' => 'Body', 'extérieur' => 'Exterior', 'exterieur' => 'Exterior',
        'intérieur' => 'Interior', 'interieur' => 'Interior', 'habitacle' => 'Cabin',
        'accessoires' => 'Accessories', 'divers' => 'Misc',
        'électricité' => 'Electrical', 'electricite' => 'Electrical', 'électronique' => 'Electronics', 'electronique' => 'Electronics',
        'transmission' => 'Transmission', 'boîte' => 'Gearbox', 'boite' => 'Gearbox', 'vitesses' => 'Speeds',
        'caisse' => 'Body Shell',
        'pare-chocs' => 'Bumper', 'par chocs' => 'Bumper',
        'blanc' => 'White', 'noir' => 'Black', 'rouge' => 'Red',
        'bleu' => 'Blue', 'vert' => 'Green', 'gris' => 'Grey',
        'argent' => 'Silver', 'brillant' => 'Gloss', 'mat' => 'Matte',
        'neuf' => 'New', 'reconditionné' => 'Refurbished', 'reconditionne' => 'Refurbished',
        'occasion' => 'Used', 'origine' => 'Original', 'd’origine' => 'Original',
        'oem' => 'OEM',
        'alliage' => 'Alloy', 'aluminium' => 'Aluminium',
        'acier' => 'Steel', 'finition' => 'Finish',
        'design' => 'Design', 'sport' => 'Sport', 'sportif' => 'Sporty',
        'performance' => 'Performance',
        'puissance' => 'Power', 'couple' => 'Torque',
        'cylindrée' => 'Displacement', 'cylindre' => 'Cylinder',
        'essence' => 'Petrol', 'diesel' => 'Diesel',
        'électrique' => 'Electric', 'electrique' => 'Electric',
        'hybride' => 'Hybrid',
        'inclus' => 'Included',
        'compatible' => 'Compatible', 'compatibilité' => 'Compatibility',
        'montage' => 'Installation', 'installation' => 'Installation',
        'direct' => 'Direct', 'rapide' => 'Fast',
        'sécurisé' => 'Secure', 'securise' => 'Secure',
        'livraison' => 'Delivery', 'expédition' => 'Shipping', 'expedition' => 'Shipping',
        'gratuit' => 'Free', 'gratuite' => 'Free',
        'garantie' => 'Warranty', 'mois' => 'Months',
        'prix' => 'Price', 'disponibilité' => 'Availability', 'disponibilite' => 'Availability',
        'stock' => 'Stock', 'en stock' => 'In Stock', 'limité' => 'Limited', 'limite' => 'Limited',
        'commander' => 'Order', 'acheter' => 'Buy',
        'savoir' => 'Know', 'plus' => 'More',
        'informations' => 'Information',
        'qualité' => 'Quality', 'qualite' => 'Quality',
        'haut' => 'High', 'haute' => 'High', 'gamme' => 'Range',
        'premium' => 'Premium',
        'léger' => 'Lightweight', 'leger' => 'Lightweight',
        'résistance' => 'Resistance', 'resistance' => 'Resistance',
        'choc' => 'Impact', 'chocs' => 'Impacts',
        'durable' => 'Durable', 'durabilité' => 'Durability',
        'fiabilité' => 'Reliability', 'fiabilite' => 'Reliability',
        'fiable' => 'Reliable',
        'sécurité' => 'Safety', 'securite' => 'Safety',
        'confort' => 'Comfort',
        'conduite' => 'Driving',
        'stabilité' => 'Stability', 'stabilite' => 'Stability',
        'tenue' => 'Handling', 'route' => 'Road',
        'adhérence' => 'Grip', 'adherence' => 'Grip',
        'look' => 'Look', 'style' => 'Style', 'élégant' => 'Elegant', 'elegant' => 'Elegant',
        'moderne' => 'Modern', 'classique' => 'Classic',
        'personnalisation' => 'Customization',
        'rénové' => 'Refurbished', 'renove' => 'Refurbished',
        'restauré' => 'Restored', 'restaure' => 'Restored',
        'fabrication' => 'Manufacturing',
        'matériaux' => 'Materials', 'materiaux' => 'Materials',
        'alliages' => 'Alloys',
        'haute résistance' => 'High Strength',
        'prêt' => 'Ready', 'pret' => 'Ready',
        'démarrage' => 'Start-up', 'demarrage' => 'Start-up',
        'immédiat' => 'Immediate', 'immediat' => 'Immediate',
        'améliore' => 'Improves', 'ameliore' => 'Improves',
        'amélioration' => 'Improvement', 'amelioration' => 'Improvement',
        'esthétique' => 'Aesthetic', 'esthetique' => 'Aesthetic',
        'look' => 'Look', 'aspect' => 'Appearance',
        'visibilité' => 'Visibility', 'visibilite' => 'Visibility',
        'nuit' => 'Night',
        'brouillard' => 'Fog',
        'éclairage' => 'Lighting', 'eclairage' => 'Lighting',
        'halogène' => 'Halogen', 'halogene' => 'Halogen',
        'xénon' => 'Xenon', 'xenon' => 'Xenon',
        'led' => 'LED',
        'optique' => 'Lens', 'optiques' => 'Lenses',
        'verre' => 'Glass', 'trempé' => 'Tempered', 'trempe' => 'Tempered',
        'boîtier' => 'Housing', 'boitier' => 'Housing',
        'renforcé' => 'Reinforced', 'renforce' => 'Reinforced',
        'fixation' => 'Mounting',
        'faisceau' => 'Wiring Harness',
        'connecteurs' => 'Connectors',
        'ampoules' => 'Bulbs', 'ampoule' => 'Bulb',
        'remplaçables' => 'Replaceable', 'remplacables' => 'Replaceable',
        'large' => 'Wide', 'gamme' => 'Range',
        'modèle' => 'Model', 'modele' => 'Model', 'modèles' => 'Models', 'modeles' => 'Models',
        'tailles' => 'Sizes', 'tailles disponibles' => 'Available Sizes',
        'pouces' => 'Inch', 'inch' => 'Inch',
        'configuration' => 'Configuration',
        'entraxe' => 'PCD', 'pcd' => 'PCD',
        'largeur' => 'Width',
        'avant' => 'Front', 'arrière' => 'Rear', 'arriere' => 'Rear',
        'selon' => 'Depending on',
        'état' => 'Condition', 'etat' => 'Condition',
        'neuf' => 'New', 'reconditionné' => 'Refurbished', 'reconditionne' => 'Refurbished',
        'utilisation' => 'Use',
        'route' => 'Road', 'circuit' => 'Track',
        'sportive' => 'Sporty',
        'jeu' => 'Set', 'lot' => 'Lot',
        'complet' => 'Complete', 'set' => 'Set',
        'paire' => 'Pair',
        'remplacement' => 'Replacement',
        'ancien' => 'Old', 'anciens' => 'Old',
        'terni' => 'Dull', 'ternis' => 'Dull',
        'jauni' => 'Yellowed', 'jaunis' => 'Yellowed',
        'redonner' => 'Restore',
        'neuf' => 'New',
        'idéal' => 'Ideal', 'ideale' => 'Ideal',
        'passionnés' => 'Enthusiasts', 'passionnes' => 'Enthusiasts',
        'collectionneurs' => 'Collectors',
        'restauration' => 'Restoration',
        'améliorer' => 'Improve', 'ameliorer' => 'Improve',
        'optimiser' => 'Optimize',
        'conserver' => 'Maintain',
        'garantissant' => 'Ensuring',
        'allier' => 'Combine',
        'authenticité' => 'Authenticity', 'authenticite' => 'Authenticity',
        'exceptionnel' => 'Exceptional',
        'emblématique' => 'Iconic', 'emblematique' => 'Iconic',
        'légendaire' => 'Legendary', 'legendaire' => 'Legendary',
        'emarquable' => 'Remarkable',
        'incomparable' => 'Unmatched',
        'paramètres' => 'Settings', 'parametres' => 'Settings',
        'principales' => 'Main', 'principaux' => 'Main',
        'spécifications' => 'Specifications', 'specifications' => 'Specifications',
        'technique' => 'Technical', 'techniques' => 'Technical',
        'caractéristiques' => 'Features', 'caracteristiques' => 'Features',
        'description' => 'Description',
        'contenu' => 'Contents',
        'éléments' => 'Components', 'elements' => 'Components',
        'nécessaires' => 'Required', 'necessaires' => 'Required',
        'page' => 'Page',
        'découvrez' => 'Discover', 'decouvrez' => 'Discover',
        'visitez' => 'Visit',
        'site' => 'Site', 'officiel' => 'Official',
        'gamme' => 'Range', 'complète' => 'Complete', 'complete' => 'Complete',
        'catégorie' => 'Category', 'categorie' => 'Category',
        'cliquez' => 'Click',
        'ici' => 'Here',
        'produit' => 'Product', 'produits' => 'Products',
        'fiche' => 'Sheet',
        'services' => 'Services',
        'sav' => 'After-Sales Service',
        'contact' => 'Contact',
        'email' => 'Email',
        'téléphone' => 'Phone', 'telephone' => 'Phone',
        'adresse' => 'Address',
        'retour' => 'Return', 'retours' => 'Returns',
        'remboursement' => 'Refund',
        'échange' => 'Exchange', 'echange' => 'Exchange',
        'satisfait' => 'Satisfied',
        'conditions' => 'Terms', 'générales' => 'General', 'generales' => 'General',
        'vente' => 'Sale',
        'livraison' => 'Delivery',
        'délais' => 'Lead Times', 'delais' => 'Lead Times',
        'suivi' => 'Tracking',
        'colis' => 'Parcel',
        'endommagé' => 'Damaged', 'endommage' => 'Damaged',
        'réclamation' => 'Claim', 'reclamation' => 'Claim',
        'protection' => 'Protection',
        'données' => 'Data', 'donnees' => 'Data',
        'personnelles' => 'Personal',
        'cookies' => 'Cookies',
        'légales' => 'Legal', 'legales' => 'Legal',
        'mentions' => 'Notices',
        'plan' => 'Sitemap',
        'engines' => 'Engines', 'engine' => 'Engine',
        'voiture' => 'Car', 'véhicule' => 'Vehicle', 'vehicule' => 'Vehicle',
        'véhicules' => 'Vehicles', 'vehicules' => 'Vehicles',
        'berline' => 'Sedan', 'break' => 'Wagon',
        'cabriolet' => 'Convertible', 'coupé' => 'Coupe', 'coupe' => 'Coupe',
        'suv' => 'SUV', '4x4' => '4x4',
        'utilitaire' => 'Utility',
        'poids' => 'Weight', 'lourd' => 'Heavy',
        'léger' => 'Light', 'leger' => 'Light',
        'tourisme' => 'Passenger',
        'course' => 'Racing',
        'compétition' => 'Competition', 'competition' => 'Competition',
        'préparation' => 'Preparation', 'preparation' => 'Preparation',
        'tuning' => 'Tuning',
        'custom' => 'Custom',
        'personnalisé' => 'Customized', 'personnalise' => 'Customized',
        'sur' => 'On', 'mesure' => 'Custom',
        "d'origine" => 'Original',
        'performance' => 'Performance',
        'puissant' => 'Powerful',
        'efficace' => 'Efficient',
        'économique' => 'Economical', 'economique' => 'Economical',
        'écologique' => 'Ecological', 'ecologique' => 'Ecological',
        'respectueux' => 'Environmentally Friendly',
        'environnement' => 'Environment',
        'norme' => 'Standard', 'normes' => 'Standards',
        'euro' => 'Euro',
        'homologation' => 'Approval',
        'certifié' => 'Certified', 'certifie' => 'Certified',
        'approuvé' => 'Approved', 'approuve' => 'Approved',
        'testé' => 'Tested', 'teste' => 'Tested',
        'garanti' => 'Guaranteed',
        'satisfaction' => 'Satisfaction',
        'client' => 'Customer', 'clients' => 'Customers',
        'service' => 'Service',
        'équipe' => 'Team', 'equipe' => 'Team',
        'expert' => 'Expert', 'experts' => 'Experts',
        'technicien' => 'Technician',
        'conseil' => 'Advice', 'conseils' => 'Advice',
        'devis' => 'Quote',
        'gratuit' => 'Free',
        'paiement' => 'Payment',
        'carte' => 'Card', 'bancaire' => 'Bank',
        'paypal' => 'PayPal',
        'virement' => 'Transfer',
        'espèces' => 'Cash', 'especes' => 'Cash',
        'cb' => 'Credit Card',
        'mastercard' => 'MasterCard',
        'visa' => 'Visa',
        'amex' => 'Amex',
        'facture' => 'Invoice',
        'acompte' => 'Deposit',
        'solde' => 'Balance',
        'total' => 'Total',
        'sous-total' => 'Subtotal',
        'tva' => 'VAT',
        'taxe' => 'Tax', 'taxes' => 'Taxes',
        'frais' => 'Fees',
        'port' => 'Shipping',
        'offerte' => 'Free',
        'réduction' => 'Discount', 'reduction' => 'Discount',
        'promo' => 'Promo', 'promotion' => 'Promotion',
        'code' => 'Code',
        'bon' => 'Voucher',
        "d'achat" => 'Purchase',
        'achat' => 'Purchase', 'acheter' => 'Buy',
        'vendre' => 'Sell',
        'vente' => 'Sale',
        'achat immédiat' => 'Buy It Now',
        'enchères' => 'Auction', 'encheres' => 'Auction',
        'offre' => 'Offer', 'offres' => 'Offers',
        'meilleur' => 'Best',
        'prix' => 'Price',
        'comparable' => 'Competitive',
        'imbattable' => 'Unbeatable',
        'exclusif' => 'Exclusive',
        'rare' => 'Rare',
        'trouver' => 'Find',
        'recherche' => 'Search',
        'filtrer' => 'Filter',
        'trier' => 'Sort',
        'afficher' => 'Show',
        'masquer' => 'Hide',
        'résultat' => 'Result', 'resultat' => 'Result',
        'résultats' => 'Results', 'resultats' => 'Results',
        'page' => 'Page', 'pages' => 'Pages',
        'suivant' => 'Next', 'précédent' => 'Previous', 'precedent' => 'Previous',
        'premier' => 'First', 'dernier' => 'Last',
        'charger' => 'Load',
        'plus' => 'More',
        'voir' => 'View',
        'détail' => 'Detail', 'detail' => 'Detail', 'détails' => 'Details', 'details' => 'Details',
        'résumé' => 'Summary', 'resume' => 'Summary',
        'description' => 'Description',
        'informations' => 'Information',
        'complémentaires' => 'Additional', 'complementaires' => 'Additional',
        'supplémentaires' => 'Supplementary', 'supplementaires' => 'Supplementary',
        'avis' => 'Review', 'notes' => 'Ratings',
        'commentaire' => 'Comment', 'commentaires' => 'Comments',
        'évaluation' => 'Rating', 'evaluation' => 'Rating',
        'étoile' => 'Star', 'etoile' => 'Star', 'étoiles' => 'Stars', 'etoiles' => 'Stars',
        'note' => 'Rating',
        'moyenne' => 'Average',
        'client' => 'Customer',
        'acheteur' => 'Buyer', 'acheteurs' => 'Buyers',
        'vérifié' => 'Verified', 'verifie' => 'Verified',
        'publié' => 'Published', 'publie' => 'Published',
        'rédiger' => 'Write', 'rediger' => 'Write',
        'partager' => 'Share',
        'expérience' => 'Experience', 'experience' => 'Experience',
        'recommander' => 'Recommend',
        'utile' => 'Helpful',
        'répondre' => 'Reply', 'repondre' => 'Reply',
        'signaler' => 'Report',
        'modérer' => 'Moderate', 'moderer' => 'Moderate',
        'supprimer' => 'Delete',
    ];

    protected array $categoryMap = [
        'phares' => 'Electrical, Lighting and Body',
        'éclairage' => 'Electrical, Lighting and Body',
        'eclairage' => 'Electrical, Lighting and Body',
        'moteurs' => 'Engine',
        'moteur' => 'Engine',
        'jantes' => 'Tire and Wheel',
        'roues' => 'Tire and Wheel',
        'pneus' => 'Tire and Wheel',
        'sieges' => 'Interior',
        'sièges' => 'Interior',
        'intérieur' => 'Interior',
        'interieur' => 'Interior',
        'habitacle' => 'Interior',
        'volants' => 'Interior',
        'volant' => 'Interior',
        'échappement' => 'Exhaust',
        'echappement' => 'Exhaust',
        'freinage' => 'Brake',
        'suspension' => 'Suspension',
        'direction' => 'Steering',
        'transmission' => 'Transmission',
        'boîte' => 'Transmission',
        'boite' => 'Transmission',
        'carrosserie' => 'Body',
        'extérieur' => 'Body',
        'exterieur' => 'Body',
        'pare-chocs' => 'Body',
        'par chocs' => 'Body',
        'caisse' => 'Body',
        'kit carrosserie' => 'Body',
        'électricité' => 'Electrical, Charging and Starting',
        'electricite' => 'Electrical, Charging and Starting',
        'électronique' => 'Electrical, Charging and Starting',
        'electronique' => 'Electrical, Charging and Starting',
        'accessoires' => 'Accessories and Fluids',
        'divers' => 'Accessories and Fluids',
    ];

    public function handle(): int
    {
        $filePath = $this->argument('file');
        $priceMultiplier = (float) $this->option('price-multiplier');
        $minPrice = (float) $this->option('min-price');
        $downloadImages = $this->option('download-images');
        $autoDescription = $this->option('auto-description');

        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return Command::FAILURE;
        }

        $knownBrands = array_keys(config('brands'));
        $knownBrandsLower = array_map('strtolower', $knownBrands);
        $brandLookup = array_combine($knownBrandsLower, $knownBrands);

        $products = $this->parseCSV($filePath);
        $this->info('Found ' . count($products) . ' products in CSV');

        $imported = 0;
        $skipped = 0;
        $updated = 0;
        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        foreach ($products as $row) {
            $name = trim($row['Nom'] ?? '');
            $price = floatval(str_replace([',', ' '], ['.', ''], $row['Tarifregulier'] ?? '0'));
            $description = trim($row['Description'] ?? '');
            $shortDesc = trim($row['Descriptioncourte'] ?? '');
            $sku = trim($row['UGS'] ?? '');
            $categories = trim($row['Categories'] ?? '');
            $images = trim($row['Images'] ?? '');
            $published = ($row['Publie'] ?? '0') === '1';

            if (empty($name)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $finalPrice = round($price * $priceMultiplier, 2);
            if ($finalPrice < $minPrice) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $brand = $this->extractBrand($name, $brandLookup);
            $translatedName = $this->translateName($name, $brand);
            if (mb_strlen($translatedName) > 250) {
                $translatedName = mb_substr($translatedName, 0, 247) . '...';
            }

            $categoryId = $this->mapCategory($categories);

            if (empty($sku)) {
                $sku = 'WC-' . $row['ID'];
            }

            $imageUrl = null;
            $galleryUrls = [];
            if (!empty($images)) {
                $parts = array_map('trim', explode(',', $images));
                $parts = array_filter($parts);
                $first = true;
                foreach ($parts as $imgUrl) {
                    if ($first) {
                        $imageUrl = $imgUrl;
                        $first = false;
                    } else {
                        $galleryUrls[] = $imgUrl;
                    }
                }
            }

            $desc = $this->translateDescription($description, $autoDescription, $translatedName, $brand);

            try {
                $existing = Product::where('sku', $sku)->first();
                if ($existing) {
                    $existing->update([
                        'name' => $translatedName,
                        'description' => $desc,
                        'price' => $finalPrice,
                        'category_id' => $categoryId,
                        'brand' => $brand,
                        'is_active' => $published,
                        'image' => $imageUrl ?: null,
                        'gallery_images' => $this->prepareGallery($galleryUrls),
                    ]);
                    $updated++;
                } else {
                    if ($downloadImages && $imageUrl) {
                        $localImage = $this->downloadImage($imageUrl, $sku);
                        if ($localImage) {
                            $imageUrl = $localImage;
                        }
                    }

                    Product::create([
                        'sku' => $sku,
                        'name' => $translatedName,
                        'description' => $desc,
                        'price' => $finalPrice,
                        'old_price' => null,
                        'stock_quantity' => 1000,
                        'category_id' => $categoryId,
                        'brand' => $brand,
                        'compatibility' => $brand ?: '',
                        'image' => $imageUrl ?: null,
                        'gallery_images' => $this->prepareGallery($galleryUrls),
                        'is_active' => $published,
                        'is_new' => true,
                        'rating' => 0,
                        'review_count' => 0,
                    ]);
                    $imported++;
                }
            } catch (\Exception $e) {
                $this->warn("Failed to import $sku ($translatedName): " . $e->getMessage());
                $skipped++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Import complete: $imported imported, $updated updated, $skipped skipped");

        return Command::SUCCESS;
    }

    protected function prepareGallery(?array $urls): ?string
    {
        if (empty($urls) || (count($urls) === 1 && empty($urls[0]))) {
            return null;
        }
        return json_encode(array_values(array_filter($urls)));
    }

    protected function parseCSV(string $filePath): array
    {
        $f = fopen($filePath, 'r');
        $rawHeaders = fgetcsv($f);

        $headerMap = [
            'ID' => 'ID',
            'Nom' => 'Nom',
            'Publi' => 'Publie',
            'Tarifrgulier' => 'Tarifregulier',
            'Description' => 'Description',
            'Descriptioncourte' => 'Descriptioncourte',
            'UGS' => 'UGS',
            'Catgories' => 'Categories',
            'Images' => 'Images',
        ];

        $normalized = [];
        foreach ($rawHeaders as $h) {
            $ascii = preg_replace('/[^\x20-\x7E]/u', '', $h);
            $ascii = trim($ascii);
            $key = str_replace([' ', '?'], '', $ascii);
            if (isset($headerMap[$key])) {
                $normalized[] = $headerMap[$key];
            } else {
                $normalized[] = $key;
            }
        }

        $rows = [];
        while ($line = fgetcsv($f)) {
            if (count($line) === count($normalized)) {
                $rows[] = array_combine($normalized, $line);
            }
        }
        fclose($f);
        return $rows;
    }

    protected function extractBrand(string $name, array $brandLookup): ?string
    {
        $lower = strtolower($name);

        if (isset($this->brandAliases[$lower])) {
            return $this->brandAliases[$lower];
        }

        foreach ($this->brandAliases as $alias => $canonical) {
            if (str_contains($lower, $alias)) {
                return $canonical;
            }
        }

        foreach ($brandLookup as $lowerBrand => $canonical) {
            if (str_contains($lower, $lowerBrand)) {
                return $canonical;
            }
        }

        return null;
    }

    protected function translateName(string $name, ?string $brand): string
    {
        $translated = $name;

        $translated = str_replace(["\xe2\x80\x99", "\xc2\xb4", "`", "\xca\xb9"], "'", $translated);
        $translated = str_replace(["'"], "'", $translated);

        $translated = preg_replace("/d'(\w)/i", "d' $1", $translated);
        $translated = preg_replace("/l'(\w)/i", "l' $1", $translated);

        foreach ($this->frToEn as $fr => $en) {
            $translated = preg_replace('/\b' . preg_quote($fr, '/') . '\b/i', $en, $translated);
        }

        $translated = preg_replace("/d' (\w)/i", "d'$1", $translated);
        $translated = preg_replace("/l' (\w)/i", "l'$1", $translated);

        $translated = strip_tags($translated);

        $translated = preg_replace('/[<>]/', '', $translated);
        $translated = preg_replace('/\s+/', ' ', $translated);
        $translated = trim($translated);

        $translated = preg_replace('/—+|–+/', '–', $translated);

        $translated = preg_replace("/\bd['\u{2019}](\w+)\b/i", '$1 ', $translated);
        $translated = preg_replace("/\bl['\u{2019}](\w+)\b/i", '$1 ', $translated);

        $translated = preg_replace('/\s+/', ' ', $translated);
        $translated = trim($translated);

        if ($brand && !str_contains($translated, $brand)) {
            $translated = $brand . ' ' . $translated;
        }

        return $translated;
    }

    protected function translateDescription(string $description, bool $autoDescription, string $name, ?string $brand): string
    {
        if (empty($description) || trim(strip_tags($description)) === '') {
            if ($autoDescription) {
                return "Premium quality " . ($brand ? "$brand " : "") . "auto part. " . $name . ". Engineered for performance, durability, and a perfect fit. Suitable for various vehicle models.";
            }
            return '';
        }

        $desc = strip_tags($description);
        $desc = html_entity_decode($desc, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $desc = str_replace(["\xe2\x80\x99", "\xc2\xb4", "`", "\xca\xb9"], "'", $desc);
        $desc = str_replace(["'"], "'", $desc);

        foreach ($this->frToEn as $fr => $en) {
            $desc = preg_replace('/\b' . preg_quote($fr, '/') . '\b/i', $en, $desc);
        }

        $desc = preg_replace(["/\bd['\u{2019}](\w+)\b/i", "/\bl['\u{2019}](\w+)\b/i"], '$1 ', $desc);

        $desc = preg_replace('/[<>]/', '', $desc);
        $desc = preg_replace('/\s+/', ' ', $desc);
        $desc = trim($desc);

        $desc = preg_replace('/H2\s*:\s*/i', '', $desc);
        $desc = preg_replace('/H3\s*:\s*/i', '', $desc);

        $desc = preg_replace('/data-start="[^"]*"/', '', $desc);
        $desc = preg_replace('/data-end="[^"]*"/', '', $desc);
        $desc = preg_replace('/data-is-[^=]*="[^"]*"/', '', $desc);
        $desc = preg_replace('/data-turn-id="[^"]*"/', '', $desc);
        $desc = preg_replace('/data-testid="[^"]*"/', '', $desc);
        $desc = preg_replace('/data-scroll-anchor="[^"]*"/', '', $desc);
        $desc = preg_replace('/data-message-[^=]*="[^"]*"/', '', $desc);
        $desc = preg_replace('/data-message-model-slug="[^"]*"/', '', $desc);
        $desc = preg_replace('/data-turn="[^"]*"/', '', $desc);
        $desc = preg_replace('/dir="[^"]*"/', '', $desc);
        $desc = preg_replace('/tabindex="[^"]*"/', '', $desc);
        $desc = preg_replace('/class="[^"]*"/', '', $desc);
        $desc = preg_replace('/id="[^"]*"/', '', $desc);

        $lines = explode("\n", $desc);
        $cleaned = array_filter($lines, function($line) {
            $line = trim($line);
            if (empty($line)) return false;
            if (str_starts_with($line, '<')) return false;
            if (preg_match('/^(https?:\/\/)/i', $line)) return false;
            if (str_starts_with($line, '?>')) return false;
            if (str_starts_with($line, '?>')) return false;
            return true;
        });

        $desc = implode("\n", $cleaned);
        $desc = trim($desc);

        if (mb_strlen($desc) < 20) {
            if ($autoDescription) {
                return "Premium quality " . ($brand ? "$brand " : "") . "auto part. " . $name . ". Engineered for performance, durability, and a perfect fit.";
            }
            return '';
        }

        return mb_substr($desc, 0, 5000);
    }

    protected function mapCategory(string $categories): ?int
    {
        if (empty($categories)) {
            return null;
        }

        $parts = array_map('trim', explode(',', $categories));
        foreach ($parts as $part) {
            $lower = mb_strtolower(trim($part));
            if ($lower === 'uncategorized' || empty($lower)) continue;

            if (isset($this->categoryMap[$lower])) {
                $cat = Category::where('name', $this->categoryMap[$lower])->first();
                if ($cat) return $cat->id;
            }

            foreach ($this->categoryMap as $fr => $en) {
                if (str_contains($lower, $fr)) {
                    $cat = Category::where('name', $en)->first();
                    if ($cat) return $cat->id;
                }
            }
        }

        foreach ($parts as $part) {
            $clean = trim($part);
            if (empty($clean) || strtolower($clean) === 'uncategorized') continue;
            $existing = Category::where('name', $clean)->first();
            if ($existing) return $existing->id;
        }

        return null;
    }

    protected function downloadImage(string $url, string $sku): ?string
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->get($url);

            if ($response->successful()) {
                $ext = 'jpg';
                $filename = 'wc-' . $sku . '.' . $ext;
                Storage::disk('public')->put('products/' . $filename, $response->body());
                return Storage::disk('public')->url('products/' . $filename);
            }
        } catch (\Exception $e) {
            $this->warn("Failed to download image for $sku: " . $e->getMessage());
        }
        return null;
    }
}
