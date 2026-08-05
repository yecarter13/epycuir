<?php

namespace App\Services;

class AISearchService
{
    private array $synonyms;

    public function __construct()
    {
        $this->synonyms = $this->buildSynonymDictionary();
    }

    public function expandQuery(string $query): array
    {
        $original = mb_strtolower(trim($query));
        $terms = preg_split('/[\s,]+/', $original);
        $terms = array_filter($terms);
        if (empty($terms)) return [$query];

        $expanded = [];

        foreach ($terms as $term) {
            $termExpanded = $this->expandTerm($term);
            foreach ($termExpanded as $t) {
                $expanded[$t] = true;
            }
        }

        return array_keys($expanded);
    }

    public function correctTypo(string $word): string
    {
        $word = mb_strtolower(trim($word));
        if (strlen($word) < 3) return $word;

        $bestMatch = $word;
        $bestDist = PHP_INT_MAX;

        $knownWords = array_keys($this->synonyms);
        $extraWords = ['peugeot', 'renault', 'citroen', 'volkswagen', 'mercedes', 'bmw', 'audi', 'toyota', 'honda', 'nissan', 'ford', 'opel', 'fiat', 'hyundai', 'kia', 'mazda', 'subaru', 'mitsubishi', 'suzuki', 'volvo', 'skoda', 'seat', 'land rover', 'jaguar', 'mini', 'porsche', 'ferrari', 'lamborghini', 'maserati', 'alfa romeo', 'lexus', 'dacia', 'mg', 'smart', 'jeep', 'dodge', 'chevrolet', 'chrysler', 'gmc', 'ram', 'bentley', 'rolls royce', 'aston martin', 'lotus', 'caterham', 'mclaren', 'bugatti', 'koenigsegg', 'pagani', 'tesla', 'polestar', 'cupra', 'lynk', 'dfs', 'fisker', 'rivian', 'lucid', 'nio', 'xpeng', 'byd', 'great wall', 'chery', 'geely', 'saic', 'jac', 'brilliance', 'chang an', 'dongfeng', 'faw', 'haima', 'soueast', 'zotye', 'baic', 'venucia', 'naveco', 'yuejin', 'foton', 'sinotruk', 'higer', 'king long', 'yutong', 'anhui', 'jianghuai', 'zhengzhou', 'nissan diesel', 'ud trucks', 'hino', 'isuzu', 'mitsubishi fuso', 'man', 'scania', 'daf', 'iveco', 'renault trucks', 'volvo trucks', 'mercedes benz trucks'];

        $allWords = array_merge($knownWords, $extraWords);

        foreach ($allWords as $known) {
            if (strlen($known) < 3) continue;
            $dist = levenshtein($word, $known);
            $maxLen = max(strlen($word), strlen($known));
            $threshold = $maxLen <= 4 ? 1 : ($maxLen <= 7 ? 2 : 3);

            if ($dist === 0) return $word;
            if ($dist <= $threshold && $dist < $bestDist) {
                $bestDist = $dist;
                $bestMatch = $known;
            }
        }

        // Fuzzy prefix match for longer words
        if ($bestMatch === $word && strlen($word) > 4) {
            foreach ($allWords as $known) {
                if (strlen($known) < strlen($word) - 2) continue;
                $prefix = substr($known, 0, max(3, strlen($word) - 2));
                if (str_starts_with($word, substr($known, 0, 2)) && similar_text($word, $known, $pct) && $pct > 60) {
                    if (levenshtein($word, $known) < $bestDist) {
                        $bestDist = levenshtein($word, $known);
                        $bestMatch = $known;
                    }
                }
            }
        }

        return $bestMatch;
    }

    public function scoreProduct(array $product, string $query): float
    {
        $score = 0;
        $lower = mb_strtolower($query);
        $terms = $this->expandTerm($lower);

        foreach ($terms as $term) {
            $brand = mb_strtolower($product['brand'] ?? '');
            $name = mb_strtolower($product['name'] ?? '');
            $compat = mb_strtolower($product['compatibility'] ?? '');
            $sku = mb_strtolower($product['sku'] ?? '');
            $desc = mb_strtolower(mb_substr($product['description'] ?? '', 0, 500));

            // Exact brand match (highest priority)
            if ($brand === $term) $score += 100;
            elseif (str_contains($brand, $term)) $score += 50;

            // Brand in name
            if (str_contains($name, $term)) {
                if (preg_match('/\b' . preg_quote($term, '/') . '\b/', $name)) $score += 40;
                else $score += 20;
            }

            // Exact word match in name
            if (preg_match('/\b' . preg_quote($term, '/') . '\b/i', $name)) $score += 30;

            // SKU match
            if (str_contains($sku, $term)) $score += 25;

            // Compatibility match
            if (preg_match('/\b' . preg_quote($term, '/') . '\b/i', $compat)) $score += 15;
            elseif (str_contains($compat, $term)) $score += 5;

            // Description match
            if (preg_match('/\b' . preg_quote($term, '/') . '\b/i', $desc)) $score += 10;
            elseif (str_contains($desc, $term)) $score += 3;
        }

        return $score;
    }

    private function expandTerm(string $term): array
    {
        $results = [$term];

        if (isset($this->synonyms[$term])) {
            $results = array_merge($results, $this->synonyms[$term]);
        }

        // Check if any synonym key partially matches
        foreach ($this->synonyms as $key => $syns) {
            $lev = levenshtein($term, $key);
            $maxLen = max(strlen($term), strlen($key));
            if ($maxLen > 3 && $lev <= 1) {
                $results = array_merge($results, [$key], $syns);
            }
            foreach ($syns as $syn) {
                if (levenshtein($term, $syn) <= 1) {
                    $results = array_merge($results, [$key], $syns);
                    break;
                }
            }
        }

        return array_unique($results);
    }

    private function buildSynonymDictionary(): array
    {
        return [
            // French to English automotive terms
            'pneu' => ['tyre', 'tire', 'wheel', 'roue', 'pneumatique', 'tyres', 'tires', 'rubber'],
            'penu' => ['pneu', 'tyre', 'tire', 'wheel', 'roue', 'pneumatique'],
            'roue' => ['wheel', 'tyre', 'tire', 'pneu', 'rim', 'jante'],
            'jante' => ['rim', 'wheel', 'alloy', 'aluminium'],
            'moteur' => ['engine', 'motor', 'moteur'],
            'frein' => ['brake', 'brakes', 'braking', 'freins', 'disque', 'pad', 'plaquette'],
            'freins' => ['brake', 'brakes', 'braking', 'frein', 'disque', 'pad', 'plaquette'],
            'plaquette' => ['pad', 'brake pad', 'brake pads', 'plaquettes'],
            'plaquettes' => ['pad', 'brake pad', 'brake pads', 'plaquette'],
            'disque' => ['disc', 'brake disc', 'rotor', 'disques', 'disk'],
            'disques' => ['disc', 'brake disc', 'rotor', 'disque', 'disk'],
            'embrayage' => ['clutch', 'embrayage'],
            'suspension' => ['suspension', 'shock', 'absorber', 'amortisseur', 'suspensions', 'struts'],
            'amortisseur' => ['shock', 'absorber', 'damper', 'suspension', 'struts'],
            'amortisseurs' => ['shock', 'absorber', 'damper', 'suspension', 'struts'],
            'echappement' => ['exhaust', 'silencer', 'muffler'],
            'silencieux' => ['exhaust', 'silencer', 'muffler', 'echappement'],
            'direction' => ['steering', 'rack', 'column'],
            'cremaillere' => ['steering rack', 'steering', 'rack'],
            'volant' => ['steering wheel', 'flywheel', 'volant moteur'],
            'boite' => ['gearbox', 'transmission', 'boite de vitesses', 'gear'],
            'boite de vitesses' => ['gearbox', 'transmission', 'boite', 'gear'],
            'vitesse' => ['gear', 'speed', 'transmission'],
            'batterie' => ['battery', 'batterie'],
            'alternateur' => ['alternator', 'alternateur'],
            'demarreur' => ['starter', 'starter motor', 'demarreur'],
            'courroie' => ['belt', 'timing belt', 'serpentine belt', 'drive belt', 'courroies'],
            'courroies' => ['belt', 'timing belt', 'courroie'],
            'distribution' => ['timing belt', 'timing chain', 'distribution kit', 'courroie distribution'],
            'chaine' => ['chain', 'timing chain', 'chaine distribution'],
            'bougie' => ['spark plug', 'ignition', 'bougies', 'plug'],
            'bougies' => ['spark plug', 'ignition', 'bougie', 'plug'],
            'filtre' => ['filter', 'filtre huile', 'filtre air', 'filtre essence', 'oil filter', 'air filter'],
            'filtre huile' => ['oil filter', 'filtre', 'filtre a huile'],
            'filtre air' => ['air filter', 'filtre', 'filtre a air'],
            'huile' => ['oil', 'engine oil', 'lubricant', '5w30', '10w40'],
            'refroidissement' => ['cooling', 'radiator', 'coolant', 'thermostat', 'water pump'],
            'radiateur' => ['radiator', 'cooling', 'intercooler'],
            'thermostat' => ['thermostat', 'cooling', 'temperature'],
            'pompe a eau' => ['water pump', 'pompe', 'cooling'],
            'pompe' => ['pump', 'water pump', 'fuel pump', 'oil pump'],
            'injection' => ['injection', 'fuel injector', 'injector', 'common rail'],
            'injecteur' => ['injector', 'fuel injector', 'injection', 'common rail'],
            'turbo' => ['turbocharger', 'turbo', 'turbo charger', 'turbine'],
            'turbocompresseur' => ['turbocharger', 'turbo', 'turbo charger'],
            'compresseur' => ['compressor', 'supercharger', 'ac compressor'],
            'climatisation' => ['air conditioning', 'ac', 'climate', 'clim', 'climatronic'],
            'clim' => ['air conditioning', 'ac', 'climate', 'climatisation'],
            'essieu' => ['axle', 'driveshaft', 'halfshaft', 'essieu'],
            'cardan' => ['driveshaft', 'cv joint', 'joint', 'cardan shaft'],
            'joint' => ['gasket', 'seal', 'joint de culasse', 'joint spi', 'cv joint'],
            'joint de culasse' => ['head gasket', 'gasket', 'cylinder head gasket', 'joint'],
            'culasse' => ['cylinder head', 'head', 'culasse'],
            'piston' => ['piston', 'pistons', 'connecting rod', 'bielle'],
            'bielle' => ['connecting rod', 'piston', 'bielle'],
            'vilebrequin' => ['crankshaft', 'vilebrequin'],
            'arbre a came' => ['camshaft', 'cam', 'arbre a came'],
            'soupape' => ['valve', 'valves', 'soupapes'],
            'soupapes' => ['valve', 'valves', 'soupape'],
            'segment' => ['piston ring', 'segment', 'rings'],
            'cylindre' => ['cylinder', 'bore'],
            'carburateur' => ['carburettor', 'carburetor', 'carb'],
            'allumage' => ['ignition', 'allumage', 'distributor', 'delco'],
            'delco' => ['distributor', 'ignition', 'allumage', 'delco'],
            'bobine' => ['coil', 'ignition coil', 'bobine allumage'],
            'alternateur' => ['alternator', 'alternateur'],
            'regulateur' => ['regulator', 'voltage regulator'],
            'phare' => ['headlight', 'light', 'lamp', 'phares'],
            'phares' => ['headlight', 'light', 'lamp', 'phare'],
            'feu' => ['light', 'tail light', 'indicator', 'feux', 'lamp'],
            'feux' => ['light', 'tail light', 'indicator', 'feu', 'lamp'],
            'clignotant' => ['indicator', 'turn signal', 'blinker', 'clignotants'],
            'retroviseur' => ['mirror', 'rear view mirror', 'wing mirror', 'retroviseurs'],
            'pare brise' => ['windscreen', 'windshield', 'glass', 'parebrise'],
            'essuie glace' => ['wiper', 'windscreen wiper', 'wiper blade', 'essuieglace'],
            'liquide lave glace' => ['screen wash', 'washer fluid', 'windscreen washer'],
            'radiateur chauffage' => ['heater core', 'heater matrix', 'heating'],
            'chauffage' => ['heater', 'heating', 'blower', 'ventilation'],
            'ventilateur' => ['fan', 'cooling fan', 'radiator fan', 'ventilateur'],
            'pompe injection' => ['injection pump', 'fuel pump', 'diesel pump'],
            'rampe injection' => ['fuel rail', 'common rail', 'injection'],
            'prechauffage' => ['glow plug', 'preheating', 'prechauffage'],
            'bougie prechauffage' => ['glow plug', 'preheating', 'prechauffage'],
            'echangeur' => ['intercooler', 'heat exchanger', 'exchangeur'],
            'sonde' => ['sensor', 'probe', 'lambda', 'oxygen sensor'],
            'sonde lambda' => ['lambda sensor', 'oxygen sensor', 'o2 sensor', 'sonde'],
            'capteur' => ['sensor', 'probe', 'capteur'],
            'calculateur' => ['ecu', 'engine control unit', 'computer', 'ecu'],
            'boitier' => ['ecu', 'control unit', 'module', 'boitier'],
            'faisceau' => ['wiring', 'harness', 'loom', 'faisceau electrique'],
            'relais' => ['relay', 'relais'],
            'fusible' => ['fuse', 'fusible'],
            'alternateur' => ['alternator', 'alternateur'],
            'courroie alternateur' => ['alternator belt', 'serpentine belt', 'drive belt'],
            'galet' => ['tensioner', 'pulley', 'roller', 'galet tendeur'],
            'galet tendeur' => ['tensioner', 'pulley', 'roller', 'galet'],
            'tendeur' => ['tensioner', 'tensioner pulley', 'galet tendeur'],
            'amortisseur' => ['shock absorber', 'damper', 'shock', 'strut', 'amortisseur'],
            'ressort' => ['spring', 'coil spring', 'suspension spring', 'ressort'],
            'barre stabilisatrice' => ['anti roll bar', 'sway bar', 'stabilizer bar', 'stabilisatrice'],
            'stabilisatrice' => ['anti roll bar', 'sway bar', 'stabilizer bar'],
            'triangle' => ['control arm', 'wishbone', 'suspension arm', 'triangles'],
            'rotule' => ['ball joint', 'rotule'],
            'biellette' => ['link', 'drop link', 'tie rod', 'stabilizer link', 'biellette'],
            'biellette barre stabilisatrice' => ['drop link', 'stabilizer link', 'anti roll bar link'],
            'soufflet' => ['boot', 'gaitor', 'cv boot', 'bellows', 'soufflet'],
            'soufflet cardan' => ['cv boot', 'gaitor', 'drive shaft boot'],
            'transmission' => ['transmission', 'gearbox', 'drive shaft', 'transmission'],
            'differetiel' => ['differential', 'diff', 'final drive'],
            'pont' => ['axle', 'differential', 'final drive', 'pont'],
            'arbre' => ['shaft', 'axle', 'drive shaft', 'half shaft', 'prop shaft'],
            'arbre transmission' => ['prop shaft', 'drive shaft', 'transmission shaft'],
            'palier' => ['bearing', 'support bearing', 'centre bearing'],
            'roulement' => ['bearing', 'wheel bearing', 'hub bearing'],
            'roulement roue' => ['wheel bearing', 'hub bearing', 'bearing'],
            'cardan' => ['cv joint', 'drive shaft', 'constant velocity joint'],
            'soufflet cardan' => ['cv boot', 'gaitor', 'drive shaft boot'],
            'joint homocinetique' => ['cv joint', 'homokinetic joint', 'cardan'],

            // Common misspellings and variations
            'sterin' => ['steering', 'direction'],
            'sterring' => ['steering', 'direction'],
            'stearing' => ['steering', 'direction'],
            'brak' => ['brake', 'brakes', 'frein'],
            'brek' => ['brake', 'brakes', 'frein'],
            'suspenssion' => ['suspension', 'suspension'],
            'suspention' => ['suspension', 'suspension'],
            'shock' => ['shock absorber', 'damper', 'amortisseur'],
            'engin' => ['engine', 'moteur'],
            'exaust' => ['exhaust', 'echappement'],
            'clutch' => ['clutch', 'embrayage'],
            'radiator' => ['radiator', 'cooling', 'radiateur'],
            'tyr' => ['tyre', 'tire', 'pneu'],
            'tir' => ['tyre', 'tire', 'pneu'],
            'tire' => ['tyre', 'tyres', 'pneu'],
            'tyres' => ['tyre', 'tyres', 'pneu', 'tire'],
            'wheel' => ['tyre', 'tire', 'pneu', 'rim', 'jante', 'roue'],
            'battry' => ['battery', 'batterie'],
            'batri' => ['battery', 'batterie'],
            'shock absorber' => ['shock', 'damper', 'amortisseur', 'suspension'],
            'absorber' => ['shock absorber', 'amortisseur', 'damper'],
            'damper' => ['shock absorber', 'amortisseur'],
            'strut' => ['suspension strut', 'shock absorber', 'amortisseur', 'suspension'],
            'struts' => ['suspension strut', 'shock absorber', 'amortisseur', 'suspension'],
            'break' => ['brake', 'frein'],
            'breakes' => ['brake', 'frein'],
            'breaks' => ['brake', 'frein'],
            'light' => ['lamp', 'phare', 'feu', 'headlight', 'bulb'],
            'lamp' => ['light', 'bulb', 'phare', 'feu', 'headlight'],
            'bulb' => ['light', 'lamp', 'phare', 'feu', 'headlight'],
            'miror' => ['mirror', 'retroviseur'],
            'mirror' => ['retroviseur', 'miror'],
            'windshield' => ['windscreen', 'pare brise', 'glass'],
            'whipper' => ['wiper', 'essuie glace', 'windscreen wiper'],
            'wiper' => ['windscreen wiper', 'essuie glace', 'wiper blade'],
            'dics' => ['disc', 'disque', 'brake disc'],
            'disk' => ['disc', 'disque', 'brake disc'],
            'dics brake' => ['brake disc', 'disc', 'disque frein'],
            'disk brake' => ['brake disc', 'disc', 'disque frein'],
            'pad' => ['brake pad', 'plaquette', 'plaquettes'],
            'pads' => ['brake pad', 'plaquette', 'plaquettes'],
            'chat' => ['cat', 'catalytic converter', 'catalyst'],
            'catalyst' => ['catalytic converter', 'cat', 'catalyseur'],
            'catalyseur' => ['catalytic converter', 'cat', 'catalyst'],
            'diesel' => ['diesel', 'diesel engine', 'diesel parts'],
            'petrol' => ['petrol', 'gasoline', 'essence'],
            'essence' => ['petrol', 'gasoline'],
            'gazoil' => ['diesel', 'diesel engine', 'gazole'],
            'gazole' => ['diesel', 'gazoil'],
            'huile moteur' => ['engine oil', 'oil', 'huile'],
            'liquide frein' => ['brake fluid', 'dot 4', 'dot 5.1'],
            'liquide refroidissement' => ['coolant', 'antifreeze', 'cooling'],
            'antigel' => ['antifreeze', 'coolant', 'liquide refroidissement'],
            'lave glace' => ['screen wash', 'washer fluid', 'windscreen washer'],
            'additif' => ['additive', 'fuel additive', 'oil additive'],
            'graisse' => ['grease', 'lubricant'],
            'vidange' => ['oil change', 'service', 'moteur'],
            'kit distribution' => ['timing belt kit', 'timing belt', 'distribution'],
            'courroie distribution' => ['timing belt', 'distribution', 'courroie'],
            'chaine distribution' => ['timing chain', 'chain', 'distribution'],
            'pompe eau' => ['water pump', 'coolant pump', 'pompe a eau', 'pompe'],
            'boite a eau' => ['expansion tank', 'coolant tank', 'header tank'],
            'vasistas' => ['sunroof', 'sun roof'],
            'toit ouvrant' => ['sunroof', 'sun roof', 'vasistas'],
            'vitre' => ['window', 'glass', 'vitres'],
            'vitres' => ['window', 'glass', 'vitre'],
            'portiere' => ['door', 'door panel', 'portieres'],
            'portieres' => ['door', 'door panel', 'portiere'],
            'serrure' => ['lock', 'door lock', 'serrure'],
            'poignee' => ['handle', 'door handle', 'poignees'],
            'poignees' => ['handle', 'door handle', 'poignee'],
            'charniere' => ['hinge', 'door hinge', 'charnieres'],
            'charnieres' => ['hinge', 'door hinge', 'charniere'],
            'capot' => ['bonnet', 'hood', 'capot'],
            'coffre' => ['boot', 'trunk', 'coffre'],
            'hayon' => ['tailgate', 'boot lid', 'hatch', 'hayon'],
            'aileron' => ['spoiler', 'wing', 'aileron'],
            'becquet' => ['spoiler', 'wing', 'becquet'],
            'calandre' => ['grille', 'grill', 'calandre'],
            'pare choc' => ['bumper', 'bumper cover', 'parechoc'],
            'parechoc' => ['bumper', 'bumper cover', 'pare choc'],
            'garde boue' => ['mud flap', 'mudguard', 'garde boue'],
            'bas de caisse' => ['side skirt', 'rocker panel'],
            'aile' => ['wing', 'fender', 'ailes'],
            'ailes' => ['wing', 'fender', 'aile'],
            'passage roue' => ['wheel arch', 'wheel well'],
            'renfort' => ['reinforcement', 'reinforcement plate'],
            'collecteur' => ['manifold', 'exhaust manifold', 'intake manifold', 'collecteur'],
            'collecteur admission' => ['intake manifold', 'inlet manifold', 'collecteur'],
            'collecteur echappement' => ['exhaust manifold', 'header', 'collecteur'],
            'tubulure' => ['manifold', 'tubular manifold', 'tubulure'],
            'watter pump' => ['water pump', 'pompe a eau'],
            'waterpump' => ['water pump', 'pompe a eau'],
            'altenator' => ['alternator', 'alternateur'],
            'alternater' => ['alternator', 'alternateur'],
            'startre' => ['starter', 'starter motor', 'demarreur'],
            'starteur' => ['starter', 'starter motor', 'demarreur'],
            'distributor' => ['distributor', 'delco', 'allumage'],
            'distributeur' => ['distributor', 'delco', 'allumage'],
            'ignition' => ['allumage', 'ignition coil', 'bobine'],
            'spark' => ['spark plug', 'bougie', 'ignition'],
            'injecton' => ['injection', 'injector', 'fuel injector'],
            'injecter' => ['injector', 'injection', 'fuel injector'],
            'common rail' => ['injection', 'fuel rail', 'commonrail'],
            'commonrail' => ['common rail', 'injection', 'fuel rail'],
            'diesel pump' => ['injection pump', 'diesel pump', 'pompe injection'],
            'turbine' => ['turbo', 'turbocharger', 'turbine'],
            'turbo chargeur' => ['turbocharger', 'turbo', 'turbocompresseur'],
            'wastegate' => ['wastegate', 'turbo', 'turbocharger'],
            'dump valve' => ['dump valve', 'blow off valve', 'turbo'],
            'blow off' => ['blow off valve', 'dump valve', 'turbo'],
            'intercooler' => ['intercooler', 'cooler', 'echangeur'],
            'air filter' => ['air filter', 'filtre air'],
            'oil filter' => ['oil filter', 'filtre huile'],
            'fuel filter' => ['fuel filter', 'filtre essence', 'filtre gasoil'],
            'pollen filter' => ['cabin filter', 'pollen filter', 'habitat filter', 'filtre habitacle'],
            'filtre habitacle' => ['cabin filter', 'pollen filter', 'filtre pollen'],
            'filtre pollen' => ['cabin filter', 'pollen filter', 'filtre habitacle'],
            'cabin filter' => ['pollen filter', 'filtre habitacle', 'filtre pollen'],
            'gasket' => ['joint', 'seal', 'joint de culasse'],
            'seal' => ['joint', 'gasket', 'seal'],
            'head gasket' => ['joint de culasse', 'gasket', 'culasse'],
            'exhaust gasket' => ['joint echappement', 'gasket', 'exhaust'],
            'intake gasket' => ['joint admission', 'gasket', 'intake'],
            'manifold gasket' => ['joint collecteur', 'gasket', 'manifold'],
            'oil seal' => ['joint spi', 'seal', 'oil seal'],
            'joint spi' => ['oil seal', 'seal', 'joint'],
            'bearing' => ['roulement', 'bearing', 'palier'],
            'ball bearing' => ['roulement a billes', 'bearing', 'roulement'],
            'wheel bearing' => ['roulement roue', 'bearing', 'wheel bearing'],
            'hub bearing' => ['roulement roue', 'bearing', 'wheel bearing'],
        ];
    }
}
