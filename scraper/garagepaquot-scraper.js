const fs = require('fs');
const path = require('path');

const args = process.argv.slice(2);
const BASE_URL = 'https://shop.garagepaquotsarl.fr';
const TARGET_URL = args.find(a => a.startsWith('http')) || BASE_URL + '/shop/';
const MAX_PAGES = parseInt(args.find(a => a.startsWith('--max-pages='))?.split('=')[1]) || 0;
const WITH_DETAILS = args.includes('--with-details');
const WITH_IMAGES = args.includes('--with-images');
const DELAY = parseInt(args.find(a => a.startsWith('--delay='))?.split('=')[1]) || 1000;
const OUTPUT = args.find(a => a.startsWith('--out='))?.split('=')[1] ||
               path.join(__dirname, '..', 'storage', 'app', 'garagepaquot-products.json');

const KNOWN_BRANDS = [
  'Peugeot', 'Citroën', 'Citroen', 'BMW', 'Mini', 'Volkswagen', 'Audi', 'Seat', 'Skoda',
  'Renault', 'Dacia', 'Opel', 'Ford', 'Fiat', 'Alfa Romeo', 'Lancia', 'Ferrari', 'Maserati',
  'Toyota', 'Honda', 'Nissan', 'Mazda', 'Mitsubishi', 'Subaru', 'Suzuki', 'Hyundai', 'Kia',
  'Mercedes', 'Mercedes-Benz', 'Volvo', 'Jaguar', 'Land Rover', 'Porsche', 'Lamborghini',
  'Bentley', 'Rolls-Royce', 'Bugatti', 'Aston Martin', 'Lotus', 'Daihatsu', 'Smart',
  'Bilstein', 'Bosch', 'Valeo', 'Sachs', 'ZF', 'TRW', 'Delphi', 'Denso', 'Hella',
  'Febi', 'Lemförder', 'Lemforder', 'SKF', 'Gates', 'Dayco', 'ContiTech', 'Elring',
  'Mahle', 'NGK', 'Champion', 'Eyquem', 'Beru', 'Magneti Marelli', 'Brembo',
  'Ferodo', 'Textar', 'Mintex', 'ATE', 'Luk', 'LuK', 'SACHS', 'Valeo', 'Monroe',
  'KYB', 'Meyle', 'Febi Bilstein', 'Swag', 'JP Group', 'Stabilus', 'Mapco',
  'Ruville', 'Corteco', 'Nissens', 'Behr', 'Hella', 'Pierburg', 'Wahler',
  'Motrio', 'QH', 'Quinton Hazell', 'First Line', 'Borg & Beck', 'Aisin',
  'Speedline Corse', 'Fondmetal', 'OZ Racing', 'Ronal', 'ATS', 'BBS',
  'Autec', 'Momo', 'Compomotive', 'Braid', 'Team Dynamics', 'Sparco',
  'Recaro', 'OMP', 'Sabelt', 'Willans', 'Piper', 'Kent Cams', 'Catcams',
  'Newman', 'Arrow', 'Wossner', 'JE Pistons', 'Ross Pistons', 'CP Pistons',
  'Carillo', 'Pauter', 'Maxspeedingrods', 'ACL', 'King', 'Glyco', 'Kolbenschmidt',
  'INA', 'NTN', 'NSK', 'GKN', 'Spidan', 'Loctite', 'Liqui Moly', 'Motul',
  'Elf', 'Total', 'Castrol', 'Shell', 'Mobil 1', 'Eurol', 'Würth', 'Wurth',
  '3M', 'Turtle Wax', 'Meguiars', 'Autoglym', 'Sonax', 'Koch Chemie',
  'Sika', 'Teroson', 'Bostik', 'Dinitrol', 'Waxoyl', 'Fenix', 'Autostyle',
  'Raiden', 'BMC', 'K&N', 'Green Filters', 'ITG', 'Pipercross', 'Simota',
  'Jetex', 'Supersprint', 'Sebring', 'Bosal', 'Walker', 'Remus', 'Eisenmann',
  'Milltek', 'Scorpion', 'Powerflow', 'Longlife', 'Devil', 'MG-Race',
  'BTB Exhaust', 'Kam Racing', 'Rallyart', 'WRC', 'Group N', 'Gr N',
  'Citroën Racing', 'Peugeot Sport', 'Peugeot Rallye', 'Salomon',
  'Yokohama', 'Michelin', 'Bridgestone', 'Pirelli', 'Goodyear', 'Continental',
  'Dunlop', 'Hankook', 'Kumho', 'Toyo', 'Nankang', 'Federal', 'Maxxis',
  'Avon', 'Vredestein', 'BFGoodrich', 'Firestone', 'Semperit', 'Kleber',
  'Engis', 'IKA', 'Turbo Technics', 'Garrett', 'IHI', 'KKK', 'BorgWarner',
  'Turbonetics', 'Precision Turbo', 'Comp Turbo', 'CR Performance',
  'Arrow Precision', 'Wossner', 'Pulsar', 'TiAL', 'HKS', 'Greddy', 'Blitz',
  'Trust', 'Garage Paquot', 'GP',
];

const BRAND_ALIASES = {
  'peugeot': 'Peugeot', 'citroën': 'Citroën', 'citroen': 'Citroën',
  'bmw': 'BMW', 'mini': 'Mini', 'volkswagen': 'Volkswagen', 'vw': 'Volkswagen',
  'audi': 'Audi', 'seat': 'Seat', 'skoda': 'Skoda', 'renault': 'Renault',
  'dacia': 'Dacia', 'opel': 'Opel', 'ford': 'Ford', 'fiat': 'Fiat',
  'alfa': 'Alfa Romeo', 'lancia': 'Lancia', 'ferrari': 'Ferrari',
  'maserati': 'Maserati', 'toyota': 'Toyota', 'honda': 'Honda',
  'nissan': 'Nissan', 'mazda': 'Mazda', 'mitsubishi': 'Mitsubishi',
  'subaru': 'Subaru', 'suzuki': 'Suzuki', 'hyundai': 'Hyundai', 'kia': 'Kia',
  'mercedes': 'Mercedes', 'volvo': 'Volvo', 'jaguar': 'Jaguar',
  'porsche': 'Porsche', 'lamborghini': 'Lamborghini', 'bentley': 'Bentley',
  'aston': 'Aston Martin', 'lotus': 'Lotus', 'smart': 'Smart',
  'bilstein': 'Bilstein', 'bosch': 'Bosch', 'valeo': 'Valeo',
  'sachs': 'Sachs', 'zf': 'ZF', 'trw': 'TRW', 'delphi': 'Delphi',
  'denso': 'Denso', 'hella': 'Hella', 'febi': 'Febi',
  'skf': 'SKF', 'gates': 'Gates', 'dayco': 'Dayco',
  'brembo': 'Brembo', 'ferodo': 'Ferodo', 'monroe': 'Monroe',
  'kyb': 'KYB', 'ngk': 'NGK', 'champion': 'Champion',
  'speedline': 'Speedline Corse', 'fondmetal': 'Fondmetal',
  'oz racing': 'OZ Racing', 'oz': 'OZ Racing',
  'citroën racing': 'Citroën Racing', 'peugeot sport': 'Peugeot Sport',
  'peugeot rallye': 'Peugeot Rallye',
};

const FR_TO_EN = {
  'collecteur': 'Manifold', 'admission': 'Intake', "d'admission": 'Intake',
  "d'echappement": 'Exhaust', 'echappement': 'Exhaust',
  'papillon': 'Throttle Body', 'papillons': 'Throttle Bodies',
  'plenum': 'Plenum', 'injection': 'Injection',
  'filtre': 'Filter', 'filtres': 'Filters', 'injecteurs': 'Injectors',
  'moteur': 'Engine', 'moteurs': 'Engines',
  'complet': 'Complete', 'avec': 'With',
  'phares': 'Headlights', 'phare': 'Headlight',
  'antibrouillards': 'Fog Lights', 'antibrouillard': 'Fog Light',
  'avant': 'Front', 'arrière': 'Rear', 'arriere': 'Rear',
  'kit': 'Kit', 'jantes': 'Rims', 'jante': 'Rim',
  'roues': 'Wheels', 'roue': 'Wheel',
  'pneu': 'Tire', 'pneus': 'Tires', 'pneumatiques': 'Tires',
  'sièges': 'Seats', 'siège': 'Seat', 'sieges': 'Seats', 'siege': 'Seat',
  'volant': 'Steering Wheel', 'volants': 'Steering Wheels',
  'frein': 'Brake', 'freins': 'Brakes', 'freinage': 'Braking',
  'disques': 'Discs', 'disque': 'Disc', 'plaquettes': 'Pads',
  'plaquette': 'Pad', 'étriers': 'Calipers', 'etriers': 'Calipers',
  'étrier': 'Calipers', 'etrier': 'Calipers',
  'suspension': 'Suspension', 'amortisseurs': 'Shock Absorbers',
  'amortisseur': 'Shock Absorber', 'ressorts': 'Springs', 'ressort': 'Spring',
  'train': 'Axle', 'trains': 'Axles', 'roulant': 'Running',
  'direction': 'Steering', 'crémaillère': 'Rack', 'cremaillere': 'Rack',
  'carrosserie': 'Body', 'extérieur': 'Exterior', 'exterieur': 'Exterior',
  'intérieur': 'Interior', 'interieur': 'Interior', 'habitacle': 'Cabin',
  'accessoires': 'Accessories', 'divers': 'Misc',
  'électricité': 'Electrical', 'electricite': 'Electrical',
  'électronique': 'Electronics', 'electronique': 'Electronics',
  'transmission': 'Transmission', 'boîte': 'Gearbox', 'boite': 'Gearbox',
  'vitesses': 'Speeds', 'boîte de vitesses': 'Gearbox',
  'boite de vitesses': 'Gearbox',
  'caisse': 'Body Shell', 'coque': 'Shell',
  'pare-chocs': 'Bumper', 'par chocs': 'Bumper',
  'blanc': 'White', 'blanche': 'White', 'blanches': 'White',
  'noir': 'Black', 'noire': 'Black', 'noires': 'Black',
  'rouge': 'Red', 'bleu': 'Blue', 'bleue': 'Blue',
  'vert': 'Green', 'verte': 'Green', 'gris': 'Grey', 'grise': 'Grey',
  'argent': 'Silver', 'brillant': 'Gloss', 'mat': 'Matte',
  'neuf': 'New', 'neuve': 'New',
  'reconditionné': 'Refurbished', 'reconditionne': 'Refurbished',
  'occasion': 'Used', 'origine': 'Original', "d'origine": 'Original',
  'oem': 'OEM',
  'alliage': 'Alloy', 'aluminium': 'Aluminium', 'acier': 'Steel',
  'inox': 'Stainless Steel', 'finition': 'Finish',
  'design': 'Design', 'sport': 'Sport', 'sportif': 'Sporty',
  'performance': 'Performance', 'rallye': 'Rally', 'compétition': 'Competition',
  'competition': 'Competition', 'course': 'Racing',
  'puissance': 'Power', 'couple': 'Torque',
  'cylindrée': 'Displacement', 'cylindre': 'Cylinder',
  'essence': 'Petrol', 'diesel': 'Diesel',
  'électrique': 'Electric', 'electrique': 'Electric',
  'hybride': 'Hybrid', 'inclus': 'Included',
  'compatible': 'Compatible', 'compatibilité': 'Compatibility',
  'montage': 'Installation', 'installation': 'Installation',
  'direct': 'Direct', 'rapide': 'Fast',
  'livraison': 'Delivery', 'expédition': 'Shipping', 'expedition': 'Shipping',
  'gratuit': 'Free', 'gratuite': 'Free',
  'garantie': 'Warranty',
  'prix': 'Price', 'disponibilité': 'Availability',
  'stock': 'Stock', 'en stock': 'In Stock', 'limité': 'Limited',
  'qualité': 'Quality', 'qualite': 'Quality',
  'haut': 'High', 'haute': 'High', 'gamme': 'Range',
  'premium': 'Premium', 'léger': 'Lightweight', 'leger': 'Lightweight',
  'résistance': 'Resistance', 'resistance': 'Resistance',
  'choc': 'Impact', 'chocs': 'Impacts',
  'durable': 'Durable', 'durabilité': 'Durability',
  'sécurité': 'Safety', 'securite': 'Safety',
  'confort': 'Comfort', 'conduite': 'Driving',
  'stabilité': 'Stability', 'stabilite': 'Stability',
  'tenue': 'Handling', 'route': 'Road',
  'adhérence': 'Grip', 'adherence': 'Grip',
  'look': 'Look', 'style': 'Style', 'élégant': 'Elegant', 'elegant': 'Elegant',
  'moderne': 'Modern', 'classique': 'Classic',
  'personnalisation': 'Customization', 'custom': 'Custom',
  'rénové': 'Refurbished', 'renove': 'Refurbished',
  'restauré': 'Restored', 'restaure': 'Restored',
  'fabrication': 'Manufacturing', 'matériaux': 'Materials',
  'prêt': 'Ready', 'pret': 'Ready',
  'préparation': 'Preparation', 'preparation': 'Preparation',
  'tuning': 'Tuning',
  'esthétique': 'Aesthetic', 'esthetique': 'Aesthetic',
  'aspect': 'Appearance', 'visibilité': 'Visibility',
  'éclairage': 'Lighting', 'eclairage': 'Lighting',
  'halogène': 'Halogen', 'halogene': 'Halogen',
  'xénon': 'Xenon', 'xenon': 'Xenon',
  'led': 'LED', 'optique': 'Lens', 'optiques': 'Lenses',
  'verre': 'Glass', 'trempé': 'Tempered', 'trempe': 'Tempered',
  'boîtier': 'Housing', 'boitier': 'Housing',
  'renforcé': 'Reinforced', 'renforce': 'Reinforced',
  'fixation': 'Mounting', 'faisceau': 'Wiring Harness',
  'connecteurs': 'Connectors', 'ampoules': 'Bulbs', 'ampoule': 'Bulb',
  'large': 'Wide', 'modèle': 'Model', 'modele': 'Model',
  'modèles': 'Models', 'modeles': 'Models',
  'tailles': 'Sizes', 'pouces': 'Inch', 'inch': 'Inch',
  'centimetres': 'cm', 'centimètres': 'cm', 'millimetres': 'mm',
  'configuration': 'Configuration', 'entraxe': 'PCD', 'pcd': 'PCD',
  'largeur': 'Width', 'hauteur': 'Height', 'longueur': 'Length',
  'poids': 'Weight',
  'selon': 'Depending on', 'état': 'Condition', 'etat': 'Condition',
  'utilisation': 'Use', 'circuit': 'Track',
  'jeu': 'Set', 'lot': 'Lot', 'set': 'Set', 'paire': 'Pair',
  'remplacement': 'Replacement',
  'ancien': 'Old', 'anciens': 'Old', 'terni': 'Dull', 'ternis': 'Dull',
  'jauni': 'Yellowed', 'jaunis': 'Yellowed',
  'redonner': 'Restore', 'idéal': 'Ideal', 'ideale': 'Ideal',
  'passionnés': 'Enthusiasts', 'collectionneurs': 'Collectors',
  'restauration': 'Restoration',
  'améliorer': 'Improve', 'ameliorer': 'Improve',
  'optimiser': 'Optimize',
  'authenticité': 'Authenticity', 'authenticite': 'Authenticity',
  'exceptionnel': 'Exceptional', 'emblématique': 'Iconic',
  'legendaire': 'Legendary', 'légendaire': 'Legendary',
  'spécifications': 'Specifications', 'specifications': 'Specifications',
  'technique': 'Technical', 'techniques': 'Technical',
  'caractéristiques': 'Features', 'caracteristiques': 'Features',
  'description': 'Description', 'contenu': 'Contents',
  'éléments': 'Components', 'elements': 'Components',
  'nécessaires': 'Required', 'necessaires': 'Required',
  'découvrez': 'Discover', 'decouvrez': 'Discover',
  'visitez': 'Visit', 'site': 'Site', 'officiel': 'Official',
  'complète': 'Complete', 'complete': 'Complete',
  'catégorie': 'Category', 'categorie': 'Category',
  'produit': 'Product', 'produits': 'Products',
  'services': 'Services', 'contact': 'Contact',
  'retour': 'Return', 'retours': 'Returns',
  'vente': 'Sale', 'achat': 'Purchase',
  'protection': 'Protection', 'données': 'Data',
  'voiture': 'Car', 'véhicule': 'Vehicle', 'vehicule': 'Vehicle',
  'véhicules': 'Vehicles', 'vehicules': 'Vehicles',
  'berline': 'Sedan', 'break': 'Wagon', 'cabriolet': 'Convertible',
  'coupé': 'Coupe', 'coupe': 'Coupe',
  'utilitaire': 'Utility', 'lourd': 'Heavy',
  'tourisme': 'Passenger',
  'sur': 'On', 'mesure': 'Custom',
  'efficace': 'Efficient', 'économique': 'Economical',
  'puissant': 'Powerful',
  'norme': 'Standard', 'normes': 'Standards', 'euro': 'Euro',
  'homologation': 'Approval', 'certifié': 'Certified',
  'approuvé': 'Approved', 'testé': 'Tested',
  'garanti': 'Guaranteed',
  'satisfaction': 'Satisfaction', 'client': 'Customer', 'clients': 'Customers',
  'service': 'Service', 'équipe': 'Team', 'equipe': 'Team',
  'expert': 'Expert', 'technicien': 'Technician',
  'conseil': 'Advice', 'devis': 'Quote',
  'paiement': 'Payment', 'facture': 'Invoice',
  'total': 'Total', 'tva': 'VAT',
  'frais': 'Fees', 'port': 'Shipping',
  'réduction': 'Discount', 'reduction': 'Discount',
  'promo': 'Promo', 'promotion': 'Promotion',
  'code': 'Code', 'offre': 'Offer', 'offres': 'Offers',
  'meilleur': 'Best',
  'exclusif': 'Exclusive', 'rare': 'Rare',
  'trouver': 'Find', 'recherche': 'Search',
  'afficher': 'Show', 'résultat': 'Result', 'resultat': 'Result',
  'résultats': 'Results', 'resultats': 'Results',
  'page': 'Page', 'pages': 'Pages',
  'suivant': 'Next', 'précédent': 'Previous', 'precedent': 'Previous',
  'premier': 'First', 'dernier': 'Last',
  'charger': 'Load', 'plus': 'More', 'voir': 'View',
  'détail': 'Detail', 'detail': 'Detail', 'détails': 'Details', 'details': 'Details',
  'résumé': 'Summary', 'resume': 'Summary',
  'informations': 'Information', 'complémentaires': 'Additional',
  'avis': 'Review', 'notes': 'Ratings',
  'commentaire': 'Comment', 'évaluation': 'Rating',
  'moyenne': 'Average',
  'acheteur': 'Buyer', 'vérifié': 'Verified',
  'expérience': 'Experience', 'experience': 'Experience',
  'recommander': 'Recommend', 'utile': 'Helpful',
  'paquet': 'Pack', 'pack': 'Pack', 'lot': 'Set',
  'complet': 'Complete', 'complets': 'Complete',
  'frais': 'Fresh',
  'performants': 'High-Performance', 'performant': 'High-Performance',
  'multibranches': 'Multi-Spoke',
  'coloris': 'Color', 'coloris': 'Colors',
  'quantité': 'Quantity', 'quantite': 'Quantity',
  'non inclus': 'Not Included',
  'pneumatiques': 'Tires',
  'idéales': 'Ideal', 'ideales': 'Ideal',
  'préparation': 'Preparation', 'sportive': 'Sporty',
  'autres': 'Other', 'véhicules': 'Vehicles',
  'compatibles': 'Compatible',
  'pack': 'Pack', 'lot complet': 'Complete Set',
  'diamètre': 'Diameter', 'diametre': 'Diameter',
  'acier': 'Steel', 'soudé': 'Welded', 'soude': 'Welded',
  'tube': 'Tube', 'tubular': 'Tubular',
  'défaut': 'Defect', 'default': 'Defect',
  'légèrement': 'Slightly', 'vissage': 'Screw',
  'décoratif': 'Decorative', 'decoratif': 'Decorative',
  'déflecteur': 'Deflector', 'déflecteurs': 'Deflectors',
  'pare-soleil': 'Sun Visor',
  'bas': 'Lower', 'moteur': 'Engine', 'protection': 'Protection',
  'cache': 'Cover',
  'carbone': 'Carbon', 'fibre': 'Fiber',
  'véritable': 'Genuine', 'véritable carbone': 'Real Carbon',
  'autocollant': 'Sticker', 'autocollants': 'Stickers', 'sticker': 'Sticker',
  'logo': 'Logo', 'insigne': 'Badge', 'embleme': 'Emblem',
  'decorative': 'Decorative',
  'aileron': 'Spoiler', 'ailerons': 'Spoilers',
  'becquet': 'Spoiler', 'becquets': 'Spoilers',
  'bas de caisse': 'Side Skirt',
  'élargisseur': 'Wide Arch', 'élargisseurs': 'Wide Arches',
  'aile': 'Wing', 'ailes': 'Wings',
  'capot': 'Hood', 'capots': 'Hoods',
  'portière': 'Door', 'portieres': 'Doors',
  'porte': 'Door',
  'vitre': 'Window', 'vitres': 'Windows',
  'pare-brise': 'Windscreen',
  'rétroviseur': 'Mirror', 'retroviseur': 'Mirror',
  'rétroviseurs': 'Mirrors', 'retroviseurs': 'Mirrors',
  'optique': 'Headlight', 'optiques': 'Headlights',
  'feu': 'Light', 'feux': 'Lights',
  'clignotant': 'Indicator', 'clignotants': 'Indicators',
  'stop': 'Brake Light',
  'brouillard': 'Fog Light',
  'plafonnier': 'Dome Light',
  'essuie-glace': 'Wiper', 'essuie-glaces': 'Wipers',
  'balai': 'Blade', 'balais': 'Blades',
  'moteur': 'Motor',
  'lave-glace': 'Washer',
  'pompe': 'Pump', 'pompes': 'Pumps',
  'alternateur': 'Alternator', 'alternateurs': 'Alternators',
  'démarreur': 'Starter', 'demarreur': 'Starter',
  'batterie': 'Battery', 'batteries': 'Batteries',
  'bougie': 'Spark Plug', 'bougies': 'Spark Plugs',
  'préchauffage': 'Glow Plug', 'prechauffage': 'Glow Plug',
  'faisceau': 'Wiring', 'faisceaux': 'Wiring',
  'capteur': 'Sensor', 'capteurs': 'Sensors',
  'sonde': 'Sensor', 'sondes': 'Sensors',
  'thermostat': 'Thermostat',
  'durite': 'Hose', 'durites': 'Hoses',
  'tuyau': 'Pipe', 'tuyaux': 'Pipes',
  'courroie': 'Belt', 'courroies': 'Belts',
  'distribution': 'Timing',
  'accessoire': 'Accessory',
  'galet': 'Pulley', 'galets': 'Pulleys',
  'tendeur': 'Tensioner', 'tendeurs': 'Tensioners',
  'roulement': 'Bearing', 'roulements': 'Bearings',
  'joint': 'Seal', 'joints': 'Seals',
  'joint de culasse': 'Head Gasket',
  'culasse': 'Cylinder Head',
  'carter': 'Crankcase',
  'piston': 'Piston', 'pistons': 'Pistons',
  'segment': 'Ring', 'segments': 'Rings',
  'bielle': 'Connecting Rod', 'bielles': 'Connecting Rods',
  'vilebrequin': 'Crankshaft',
  'arbre': 'Shaft', 'arbres': 'Shafts',
  'came': 'Cam', 'cames': 'Cams',
  'arbre à cames': 'Camshaft',
  'poulie': 'Pulley', 'poulies': 'Pulleys',
  'réglable': 'Adjustable', 'reglable': 'Adjustable',
  'soupape': 'Valve', 'soupapes': 'Valves',
  'ressort de soupape': 'Valve Spring',
  'coupelle': 'Retainer',
  'culbuteur': 'Rocker Arm', 'culbuteurs': 'Rocker Arms',
  'tige': 'Rod', 'tiges': 'Rods',
  'poussoir': 'Tappet', 'poussoirs': 'Tappets',
  'arbre à cames': 'Camshaft',
  'joint spi': 'Oil Seal',
  'joint torque': 'O-Ring',
  'carter d\'huile': 'Oil Pan',
  'pompe à huile': 'Oil Pump',
  'pompe à eau': 'Water Pump',
  'refroidissement': 'Cooling',
  'radiateur': 'Radiator', 'radiateurs': 'Radiators',
  'ventilateur': 'Fan', 'ventilateurs': 'Fans',
  'moteur': 'Motor',
  'embrayage': 'Clutch',
  'butée': 'Release Bearing', 'butee': 'Release Bearing',
  'disque d\'embrayage': 'Clutch Disc',
  'mécanisme': 'Pressure Plate', 'mecanisme': 'Pressure Plate',
  'volant moteur': 'Flywheel',
  'bi-masse': 'Dual Mass',
  'arbre de transmission': 'Driveshaft',
  'cardan': 'CV Joint',
  'soufflet': 'Boot', 'soufflets': 'Boots',
  'transmission': 'Driveshaft',
  'différentiel': 'Differential', 'differentiel': 'Differential',
  'pont': 'Axle',
  'barre': 'Bar', 'stabilisatrice': 'Stabilizer',
  'stabilisateur': 'Stabilizer Bar',
  'biellette': 'Link', 'biellettes': 'Links',
  'silentbloc': 'Bush', 'silentblocs': 'Bushes',
  'triangle': 'Control Arm',
  'bras': 'Arm',
  'rotule': 'Ball Joint',
  'cardan': 'CV Joint',
  'soufflet': 'Boot',
  'amortisseur': 'Shock Absorber',
  'amortisseurs': 'Shock Absorbers',
  'ressort': 'Spring', 'ressorts': 'Springs',
  'coupelle d\'amortisseur': 'Strut Mount',
  'séparateur': 'Spacer', 'separateur': 'Spacer',
  'entretoise': 'Spacer', 'entretoises': 'Spacers',
  'jambage': 'Strut',
  'fusée': 'Hub', 'fusee': 'Hub',
  'moyeux': 'Hub', 'moyeu': 'Hub',
  'étrier': 'Caliper',
  'piston': 'Piston',
  'plaquette': 'Brake Pad',
  'plaquettes': 'Brake Pads',
  'disque': 'Brake Disc',
  'disques': 'Brake Discs',
  'tambour': 'Drum',
  'mâchoire': 'Shoe', 'machoire': 'Shoe',
  'cylindre de roue': 'Wheel Cylinder',
  'flexible': 'Brake Hose',
  'liquide': 'Fluid',
  'huile': 'Oil',
  'direction': 'Steering',
  'crémaillère': 'Steering Rack',
  'colonne': 'Column',
  'cardan': 'U-Joint',
};

const CATEGORY_MAP = {
  'accessoires': 'Accessories and Fluids',
  'accessoires & electricite': 'Accessories and Fluids',
  'accessoires & électricité': 'Accessories and Fluids',
  'electricite': 'Electrical, Charging and Starting',
  'électricité': 'Electrical, Charging and Starting',
  'electronique': 'Electrical, Charging and Starting',
  'électronique': 'Electrical, Charging and Starting',
  'carrosserie': 'Body',
  'collecteur-echappement': 'Exhaust',
  'collecteur & echappement': 'Exhaust',
  'collecteur & échappement': 'Exhaust',
  'echappement': 'Exhaust',
  'échappement': 'Exhaust',
  'suspension': 'Suspension',
  'freinage': 'Brake',
  'jantes-pneus': 'Tire and Wheel',
  'jantes & pneus': 'Tire and Wheel',
  'boites-de-vitesses': 'Transmission',
  'boîtes de vitesses': 'Transmission',
  'boites de vitesses': 'Transmission',
  'transmission': 'Transmission',
  'moteurs': 'Engine',
  'moteur': 'Engine',
  'interieur': 'Interior',
  'intérieur': 'Interior',
  'habitacle': 'Interior',
  'trains-roulants': 'Suspension',
  'trains roulants': 'Suspension',
  'collecteur-dadmission': 'Air and Fuel Delivery',
  "collecteur d'admission": 'Air and Fuel Delivery',
  'boites-a-air': 'Air and Fuel Delivery',
  'boîtes à air': 'Air and Fuel Delivery',
  'echappement': 'Exhaust',
  'preparation moteur': 'Engine',
  'refroidissement': 'Belts and Cooling',
  'allumage': 'Ignition',
  'phares': 'Electrical, Lighting and Body',
  'eclairage': 'Electrical, Lighting and Body',
  'éclairage': 'Electrical, Lighting and Body',
  'sieges': 'Interior',
  'sièges': 'Interior',
  'volant': 'Interior',
  'volants': 'Interior',
  'pare-chocs': 'Body',
  'caisse': 'Body',
  'kit carrosserie': 'Body',
};

async function fetchHTML(url) {
  const controller = new AbortController();
  const timeout = setTimeout(() => controller.abort(), 30000);

  try {
    const response = await fetch(url, {
      signal: controller.signal,
      headers: {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language': 'en-US,en;q=0.9,fr;q=0.8',
        'Referer': 'https://shop.garagepaquotsarl.fr/',
      },
    });

    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return await response.text();
  } finally {
    clearTimeout(timeout);
  }
}

function extractProductsFromHTML(html) {
  const products = [];

  const gridStart = html.match(/<div[^>]*class="products wd-products[^"]*"[^>]*>/);
  if (!gridStart) return products;

  const gridSection = html.substring(gridStart.index);
  const paginationIdx = gridSection.search(/<nav\s+class="woocommerce-pagination/);
  const gridHtml = paginationIdx > -1 ? gridSection.substring(0, paginationIdx) : gridSection;

  const cardParts = gridHtml.split(/(?=<div class="wd-product wd-col)/g).filter(s => s.includes('/produit/'));

  for (const cardHtml of cardParts) {
    try {
      const idMatch = cardHtml.match(/data-id="(\d+)"/);
      const id = idMatch ? idMatch[1] : '';

      const titleMatch = cardHtml.match(/<h3 class="wd-entities-title"><a[^>]*>([\s\S]*?)<\/a><\/h3>/);
      const name = titleMatch ? titleMatch[1].trim() : '';
      if (!name) continue;

      const urlMatch = cardHtml.match(/<a href="(https:\/\/shop\.garagepaquotsarl\.fr\/produit\/[^"]+)"/);
      const productUrl = urlMatch ? urlMatch[1].replace(/&amp;/g, '&') : '';

      const priceMatch = cardHtml.match(/class="price">[^<]*<span[^>]*><bdi>([^<]+)/);
      const priceStr = priceMatch ? priceMatch[1].trim() : '0';
      const price = parseFloat(priceStr.replace(/[^0-9,]/g, '').replace(',', '.')) || 0;

      let oldPrice = null;
      const delMatch = cardHtml.match(/<del[^>]*>[\s\S]*?<bdi>([^<]+)<\/bdi>/);
      if (delMatch) {
        oldPrice = parseFloat(delMatch[1].replace(/[^0-9,]/g, '').replace(',', '.')) || null;
      }

      const thumbSection = cardHtml.match(/<div class="wd-product-thumb[^>]*>[\s\S]*?(?:<\/div>\s*){2,3}<div class="product-element-bottom/);
      const thumbHtml = thumbSection ? thumbSection[0] : cardHtml;
      const imgMatch = thumbHtml.match(/<img[^>]+src="([^"]+)"[^>]*>/);
      let image = imgMatch ? imgMatch[1].replace(/&amp;/g, '&') : '';
      if (image && !image.startsWith('http')) {
        image = BASE_URL + (image.startsWith('/') ? '' : '/') + image;
      }

      const catMatch = cardHtml.match(/<div class="wd-product-cats">[\s\S]*?<a[^>]*>([^<]+)<\/a>/);
      const category = catMatch ? catMatch[1].trim() : '';

      const catClassMatch = cardHtml.match(/product_cat-([^\s"]+)/);
      const categoryClass = catClassMatch ? catClassMatch[1] : '';

      products.push({
        id,
        name,
        price,
        old_price: oldPrice,
        image,
        category: category || null,
        categorySlug: categoryClass || null,
        productUrl,
      });
    } catch (e) {
      // skip individual card on error
    }
  }

  return products;
}

function getPaginationInfo(html) {
  const pages = html.match(/page-numbers[^>]*>(\d+)<\/a>/g);
  let lastPage = 1;
  if (pages) {
    for (const p of pages) {
      const m = p.match(/>(\d+)</);
      if (m) lastPage = Math.max(lastPage, parseInt(m[1]));
    }
  }

  const countMatch = html.match(/class="woocommerce-result-count"[^>]*>([^<]+)/);
  const countText = countMatch ? countMatch[1].trim() : '';

  const totalMatch = countText.match(/sur\s+(\d+)/);
  const total = totalMatch ? parseInt(totalMatch[1]) : null;

  return { lastPage, total, countText };
}

function buildPageUrl(baseUrl, pageNum) {
  let url = baseUrl;
  url = url.replace(/\/page\/\d+\/?/g, '/');
  url = url.replace(/\?fbclid=[^&]*&?/g, '').replace(/\?&/, '?').replace(/&$/, '').replace(/\?$/, '');
  const sep = url.includes('?') ? '&' : '/';
  if (pageNum > 1) {
    if (url.includes('?')) {
      return `${url}${sep}page=${pageNum}`;
    }
    return url.replace(/\/?$/, '') + `/page/${pageNum}/`;
  }
  return url;
}

function decodeHTMLEntities(text) {
  return text
    .replace(/&amp;/g, '&')
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>')
    .replace(/&quot;/g, '"')
    .replace(/&#039;/g, "'")
    .replace(/&#8217;/g, "'")
    .replace(/&#8211;/g, '-')
    .replace(/&#8212;/g, '--')
    .replace(/&#8230;/g, '...')
    .replace(/&#160;/g, ' ')
    .replace(/&#215;/g, '×')
    .replace(/&euro;/g, '€')
    .replace(/&agrave;/g, 'à')
    .replace(/&egrave;/g, 'è')
    .replace(/&eacute;/g, 'é')
    .replace(/&ecirc;/g, 'ê')
    .replace(/&icirc;/g, 'î')
    .replace(/&ocirc;/g, 'ô')
    .replace(/&ugrave;/g, 'ù')
    .replace(/&ucirc;/g, 'û')
    .replace(/&ccedil;/g, 'ç')
    .replace(/&laquo;/g, '«')
    .replace(/&raquo;/g, '»')
    .replace(/\\u2019/g, "'")
    .replace(/\\u2013/g, '-')
    .replace(/\\u2014/g, '--')
    .replace(/\\u00e0/g, 'à')
    .replace(/\\u00e9/g, 'é')
    .replace(/\\u00e8/g, 'è')
    .replace(/\\u00ea/g, 'ê')
    .replace(/\\u00ee/g, 'î')
    .replace(/\\u00f4/g, 'ô')
    .replace(/\\u00fb/g, 'û')
    .replace(/\\u00e7/g, 'ç')
    .replace(/\\u00e4/g, 'ä')
    .replace(/\\u00f6/g, 'ö')
    .replace(/\\u00fc/g, 'ü')
    .replace(/\\u00c9/g, 'É');
}

function extractBrand(name) {
  const lower = name.toLowerCase();

  const allBrands = [];
  for (const [alias, canonical] of Object.entries(BRAND_ALIASES)) {
    const idx = lower.indexOf(alias);
    if (idx >= 0) allBrands.push({ idx, canonical, length: alias.length });
  }
  for (const brand of KNOWN_BRANDS) {
    const idx = lower.indexOf(brand.toLowerCase());
    if (idx >= 0) allBrands.push({ idx, canonical: brand, length: brand.length });
  }

  if (allBrands.length === 0) return 'Peugeot';

  const firstHalf = name.substring(0, Math.floor(name.length / 2));
  const firstHalfBrands = allBrands.filter(b => b.idx < name.length / 2);
  if (firstHalfBrands.length > 0) {
    firstHalfBrands.sort((a, b) => b.length - a.length);
    return firstHalfBrands[0].canonical;
  }

  allBrands.sort((a, b) => b.length - a.length);
  return allBrands[0].canonical;
}

function translateName(name, brand) {
  let translated = name;

  translated = decodeHTMLEntities(translated);

  translated = translated.replace(/["']/g, "'");
  translated = translated.replace(/[–—]+/g, '-');
  translated = translated.replace(/[""]/g, '"');
  translated = translated.replace(/[""]/g, '"');

  translated = translated.replace(/\b(\d+),(\d+)\b/g, (m, p1, p2) => `${p1}.${p2}`);

  translated = translated.replace(/d'(\w)/gi, "d' $1");
  translated = translated.replace(/l'(\w)/gi, "l' $1");

  for (const [fr, en] of Object.entries(FR_TO_EN)) {
    const escaped = fr.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    translated = translated.replace(new RegExp('\\b' + escaped + '\\b', 'gi'), en);
  }

  translated = translated.replace(/d' (\w)/gi, "d'$1");
  translated = translated.replace(/l' (\w)/gi, "l'$1");

  translated = translated.replace(/\s+/g, ' ').trim();

  if (brand && !translated.toLowerCase().includes(brand.toLowerCase())) {
    translated = `${brand} ${translated}`;
  }

  translated = translated.replace(/&/g, 'and');

  return translated;
}

function translateDescription(desc, autoDescription, name, brand) {
  if (!desc || desc.trim() === '') {
    if (autoDescription) {
      return `Premium quality ${brand ? brand + ' ' : ''}auto part. ${name}. Engineered for performance, durability, and a perfect fit.`;
    }
    return '';
  }

  let d = decodeHTMLEntities(desc);
  d = d.replace(/<[^>]+>/g, ' ');
  d = d.replace(/["']/g, "'");

  d = d.replace(/d'(\w)/gi, "d' $1");
  d = d.replace(/l'(\w)/gi, "l' $1");

  for (const [fr, en] of Object.entries(FR_TO_EN)) {
    const escaped = fr.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    d = d.replace(new RegExp('\\b' + escaped + '\\b', 'gi'), en);
  }

  d = d.replace(/d' (\w)/gi, "d'$1");
  d = d.replace(/l' (\w)/gi, "l'$1");

  d = d.replace(/\s+/g, ' ').trim();
  d = d.replace(/\b\d+,\d+\b/g, m => m.replace(',', '.'));

  if (d.length < 20) {
    if (autoDescription) {
      return `Premium quality ${brand ? brand + ' ' : ''}auto part. ${name}. Engineered for performance, durability, and a perfect fit.`;
    }
    return '';
  }

  return d.substring(0, 5000);
}

function mapCategory(frenchName, frenchSlug) {
  if (!frenchName && !frenchSlug) return null;

  const lower = (frenchName || '').toLowerCase().replace(/&amp;/g, '&').trim();
  const slug = (frenchSlug || '').toLowerCase().trim();

  const mapKey = slug || lower;

  if (CATEGORY_MAP[mapKey]) {
    return CATEGORY_MAP[mapKey];
  }

  for (const [key, value] of Object.entries(CATEGORY_MAP)) {
    if (lower.includes(key) || slug.includes(key)) {
      return value;
    }
  }

  if (lower.includes('accessoires') || lower.includes('accessoire')) return 'Accessories and Fluids';
  if (lower.includes('moteur')) return 'Engine';
  if (lower.includes('frein')) return 'Brake';
  if (lower.includes('jante') || lower.includes('pneu') || lower.includes('roue')) return 'Tire and Wheel';
  if (lower.includes('carrosserie') || lower.includes('extérieur') || lower.includes('exterieur') || lower.includes('pare-chocs') || lower.includes('par chocs')) return 'Body';
  if (lower.includes('interieur') || lower.includes('intérieur') || lower.includes('siege') || lower.includes('siège') || lower.includes('volant')) return 'Interior';
  if (lower.includes('echappement') || lower.includes('échappement') || lower.includes('collecteur')) return 'Exhaust';
  if (lower.includes('suspension') || lower.includes('amortisseur') || lower.includes('train')) return 'Suspension';
  if (lower.includes('boite') || lower.includes('boîte') || lower.includes('transmission')) return 'Transmission';
  if (lower.includes('electricite') || lower.includes('électricité') || lower.includes('electronique') || lower.includes('électronique') || lower.includes('phare') || lower.includes('eclairage') || lower.includes('éclairage')) return 'Electrical, Lighting and Body';
  if (lower.includes('admission') || lower.includes('filtre') || lower.includes('injection')) return 'Air and Fuel Delivery';
  if (lower.includes('allumage') || lower.includes('bougie')) return 'Ignition';
  if (lower.includes('refroidissement') || lower.includes('radiateur') || lower.includes('courroie') || lower.includes('distribution')) return 'Belts and Cooling';

  return null;
}

async function fetchProductDetail(productUrl) {
  try {
    const html = await fetchHTML(productUrl);

    const jsonlds = [...html.matchAll(/<script type="application\/ld\+json">([\s\S]*?)<\/script>/g)].map(m => m[1]);
    let productData = null;
    let categoriesFromBreadcrumb = [];

    for (const j of jsonlds) {
      try {
        const parsed = JSON.parse(j);
        const graph = parsed['@graph'] || [parsed];
        for (const item of graph) {
          if (item['@type'] === 'Product') {
            productData = item;
          }
          if (item['@type'] === 'BreadcrumbList' && item.itemListElement) {
            categoriesFromBreadcrumb = item.itemListElement
              .filter(e => e.position > 1 && e.item && e.item.name)
              .map(e => e.item.name.replace(/&amp;/g, '&'));
          }
        }
      } catch (e) {}
    }

    let description = '';
    if (productData && productData.description) {
      description = productData.description;
    }

    if (!description) {
      const descTab = html.match(/<div[^>]*class="[^"]*woocommerce-Tabs-panel--description[^"]*"[^>]*>([\s\S]*?)<\/div>\s*<!--\s*\/woocommerce-Tabs-panel\s*-->/);
      if (descTab) {
        description = descTab[1]
          .replace(/<h2[^>]*>.*?<\/h2>/g, '')
          .replace(/<[^>]+>/g, ' ')
          .replace(/&nbsp;/g, ' ')
          .replace(/\s+/g, ' ')
          .trim();
      }
    }

    if (!description) {
      const metaDesc = html.match(/<meta name="description" content="([^"]+)"/);
      if (metaDesc) {
        description = metaDesc[1];
      }
    }

    const galleryImages = [];
    const dataSrcImages = [...html.matchAll(/data-src="([^"]+\.(?:jpg|jpeg|png|webp)[^"]*)"/g)].map(m => m[1]);
    if (dataSrcImages.length > 0) {
      for (const img of dataSrcImages) {
        if (!galleryImages.includes(img)) {
          galleryImages.push(img);
        }
      }
    }

    const fullImages = [...html.matchAll(/<img[^>]+src="([^"]+)"[^>]+class="[^"]*(?:wp-post-image|attachment-woocommerce_thumbnail)[^"]*"[^>]*>/g)].map(m => m[1]);
    for (const img of fullImages) {
      const clean = img.replace(/&amp;/g, '&');
      if (!galleryImages.includes(clean) && !clean.includes('resize=430%2C430')) {
        galleryImages.push(clean);
      }
    }

    const sku = productData && productData.sku ? String(productData.sku) : null;

    let price = null;
    let oldPrice = null;
    if (productData && productData.offers) {
      const offers = Array.isArray(productData.offers) ? productData.offers : [productData.offers];
      if (offers.length > 0) {
        const offer = offers[0];
        price = parseFloat(offer.price) || null;
        if (offers.length > 1) {
          const prices = offers.map(o => parseFloat(o.price)).filter(p => !isNaN(p)).sort((a, b) => a - b);
          if (prices.length > 1) {
            oldPrice = prices[prices.length - 1];
            price = prices[0];
          }
        }
      }
    }

    if (price === null) {
      const priceMatch = html.match(/<span class="woocommerce-Price-amount amount"><bdi>([^<]+)<\/bdi>/);
      if (priceMatch) {
        price = parseFloat(priceMatch[1].replace(/[^0-9,]/g, '').replace(',', '.')) || null;
      }
    }

    const breadcrumbCategory = categoriesFromBreadcrumb.length > 0
      ? categoriesFromBreadcrumb[categoriesFromBreadcrumb.length - 1]
      : null;

    return {
      description,
      galleryImages: galleryImages.length > 0 ? galleryImages : null,
      sku,
      price,
      oldPrice,
      category: breadcrumbCategory,
    };
  } catch (err) {
    return { description: null, galleryImages: null, sku: null, price: null, oldPrice: null, category: null };
  }
}

async function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

(async () => {
  console.log('Garage Paquot Scraper');
  console.log(`URL: ${TARGET_URL}`);
  if (WITH_DETAILS) console.log('Mode: with detailed descriptions');
  if (WITH_IMAGES) console.log('Mode: with gallery images');

  console.log('\nPage 1...');
  const html = await fetchHTML(buildPageUrl(TARGET_URL, 1));
  const pagination = getPaginationInfo(html);
  console.log(`Products: ${pagination.total || '?'} | Pages: ${pagination.lastPage}`);

  const totalPages = MAX_PAGES > 0 ? Math.min(MAX_PAGES, pagination.lastPage) : pagination.lastPage;
  let allProducts = [];

  for (let p = 1; p <= totalPages; p++) {
    try {
      const pageHtml = p === 1 ? html : await fetchHTML(buildPageUrl(TARGET_URL, p));
      const pageProducts = extractProductsFromHTML(pageHtml);

      for (const prod of pageProducts) {
        if (!allProducts.some(existing => existing.productUrl === prod.productUrl)) {
          allProducts.push(prod);
        }
      }

      process.stdout.write(`\rPage ${p}/${totalPages}: ${pageProducts.length} products (total: ${allProducts.length})`);

      if (p < totalPages && DELAY > 0) {
        await sleep(DELAY);
      }
    } catch (err) {
      console.error(`\nPage ${p} error: ${err.message}`);
      break;
    }
  }

  process.stdout.write(`\n${allProducts.length} products extracted from listings.\n`);

  if (WITH_DETAILS && allProducts.length > 0) {
    console.log(`Fetching detailed data for ${allProducts.length} products...`);
    let done = 0;
    for (const product of allProducts) {
      try {
        process.stdout.write(`\r[${++done}/${allProducts.length}] ${decodeHTMLEntities(product.name).substring(0, 50)}...`);
        const detail = await fetchProductDetail(product.productUrl);

        product.description = detail.description || null;

        if (detail.price !== null) product.price = detail.price;
        if (detail.oldPrice !== null) product.old_price = detail.oldPrice;
        if (detail.sku) product.sku = detail.sku;
        if (detail.galleryImages) product.gallery_images = detail.galleryImages;
        if (detail.category) product.category = detail.category;

        if (DELAY > 0) await sleep(DELAY);
      } catch (err) {
        process.stdout.write(`\r[${++done}/${allProducts.length}] ERROR: ${product.productUrl.substring(0, 60)}`);
      }
    }
    process.stdout.write('\n');
  }

  const outputProducts = [];
  for (const p of allProducts) {
    const brand = extractBrand(p.name);
    const translatedName = translateName(p.name, brand);
    const translatedDescription = p.description
      ? translateDescription(p.description, false, translatedName, brand)
      : null;
    const mappedCategory = mapCategory(p.category, p.categorySlug);

    const output = {
      name: translatedName.length > 250 ? translatedName.substring(0, 247) + '...' : translatedName,
      price: p.price,
      old_price: p.old_price,
      brand: brand,
      sku: p.sku || `GP-${p.id}`,
      image: p.image || null,
      gallery_images: p.gallery_images || null,
      description: translatedDescription,
      category: mappedCategory,
      stock_quantity: 10,
      is_active: true,
      source_url: p.productUrl,
      source_name: decodeHTMLEntities(p.name),
    };

    outputProducts.push(output);
  }

  const outputDir = path.dirname(OUTPUT);
  if (!fs.existsSync(outputDir)) fs.mkdirSync(outputDir, { recursive: true });
  fs.writeFileSync(OUTPUT, JSON.stringify(outputProducts, null, 2), 'utf-8');

  console.log(`\nSaved: ${OUTPUT} (${outputProducts.length} products)`);

  if (outputProducts.length > 0) {
    const s = outputProducts[0];
    console.log(`\nExample:`);
    console.log(`  Name (EN): ${s.name}`);
    console.log(`  Name (FR): ${s.source_name}`);
    console.log(`  Price:     ${s.price}€`);
    console.log(`  Brand:     ${s.brand}`);
    console.log(`  SKU:       ${s.sku}`);
    console.log(`  Category:  ${s.category}`);
    console.log(`  Image:     ${(s.image || '').substring(0, 80)}`);
    if (s.description) console.log(`  Desc:      ${s.description.substring(0, 80)}...`);

    console.log(`\nImport commands:`);
    console.log(`  php artisan products:import "${OUTPUT}" --download-images --category="Parts"`);
    console.log(`  php artisan products:import "${OUTPUT}" --download-images --update-existing --price-multiplier=1.0`);
  }
})();
