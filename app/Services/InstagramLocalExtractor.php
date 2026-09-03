<?php

namespace App\Services;

class InstagramLocalExtractor
{
    /**
     * Extract structured product data from an Instagram post caption without AI.
     */
    public function extract(string $caption): array
    {
        $cleanText = $this->clean($caption);

        return [
            'name' => $this->extractName($cleanText),
            'brand' => $this->extractBrand($cleanText),
            'category' => $this->extractCategory($cleanText),
            'price' => $this->extractPrice($caption),
            'old_price' => $this->extractOldPrice($cleanText),
            'description' => $this->buildDescription($caption),
            'specifications' => $this->extractSpecifications($cleanText),
        ];
    }

    /**
     * Returns null when the caption does not look like a saleable product.
     */
    public function shouldSkip(string $caption): bool
    {
        $t = mb_strtolower($this->clean($caption, false));
        // Explicit demand / want-to-buy posts without a price
        if (preg_match('/je suis (?:à la )?recherche|je cherche|en recherches/i', $t) && !preg_match('/\d/i', $t)) {
            return true;
        }
        if (mb_strlen(trim($t)) < 40) {
            return true;
        }
        // Empty after cleaning
        return empty($this->buildDescription($caption));
    }

    /**
     * Strip emojis, symbols, variation selectors, hashtags and boilerplate.
     */
    protected function clean(string $text, bool $stripMarkup = true): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = str_replace("\xC2\xA0", ' ', $text); // nbsp
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

        // Emojis, symbols, variation selectors
        $text = preg_replace('/\p{Cs}|\p{So}|[\x{FE0F}\x{2000}-\x{200F}]/u', '', $text);
        $text = preg_replace('/(?:[\x{1F000}-\x{1FAFF}]|[\x{1FBF0}-\x{1FBFF}])/u', '', $text);

        if ($stripMarkup) {
            // hashtags
            $text = preg_replace('/#\w+(?:[\w-]*\w)*/u', '', $text);
            $text = preg_replace('/#\S+/u', '', $text);
            // remove isolated bullet/symbol chars
            $text = preg_replace('/[•●◆▪▫│◦]/u', '', $text);
        }

        return trim($text);
    }

    /**
     * Extract a clean product name from the caption's first meaningful line.
     */
    protected function extractName(string $cleanText): string
    {
        $lines = $this->lines($cleanText);
        foreach ($lines as $line) {
            if ($this->isCtaOrBoilerplate($line)) continue;
            if (mb_strlen($line) < 3) continue;

            $name = $line;
            $name = preg_replace('/\b(à\s+vendre|a\s+vendre|for\s+sale|à\s+saisir|vendre)\b/i', ' ', $name);
            $name = preg_replace('/^\s*[-–—:|«»"\'()\[\]]+|[-–—:|«»"\'()\[\]]+\s*$/u', '', $name);
            $name = trim(preg_replace('/\s+/', ' ', $name));

            if (mb_strlen($name) < 3) continue;

            // sentence casing if it is mostly uppercase
            if ($this->isMostlyUpper($name)) {
                $name = mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, 'UTF-8');
                $name = preg_replace('/\bd[\x27 ]/', "d'", $name); // l' / D' apostrophe
            }

            if (mb_strlen($name) < 3) continue;

            return ucfirst($name);
        }

        return 'Article Sellerie';
    }

protected function extractBrand(string $cleanText): ?string
    {
        $lower = mb_strtolower($cleanText);
        $brands = [
            'cwd' => 'CWD',
            'prestige' => 'Prestige',
            'amerigo' => 'Amerigo',
            'butet' => 'Butet',
            'antares' => 'Antarès',
            'antarès' => 'Antarès',
            'erreplus' => 'Erreplus',
            'sommer' => 'Sommer',
            'stübben' => 'Stübben',
            'stubben' => 'Stübben',
            'devoucoux' => 'Devoucoux',
            'kieffer' => 'Kieffer',
            'passier' => 'Passier',
            'bates' => 'Bates',
            'wintec' => 'Wintec',
            'bruno' => 'Bruno',
            'delgrange' => 'Delgrange',
            'beaufort' => 'Beaufort',
            'samshield' => 'Samshield',
            'freejump' => 'Freejump',
            'kask' => 'Kask',
            'uvex' => 'Uvex',
            'albion' => 'Albion',
            'forestier' => 'Forestier',
            'myler' => 'Myler',
            'flex-on' => 'Flex-On',
            'flexon' => 'Flex-On',
            'voltair' => 'Voltair',
            'kep italia' => 'Kep Italia',
            'renegade' => 'Renegade',
            'evoche' => 'Evoche',
        ];

        foreach ($brands as $needle => $label) {
            if (str_contains($lower, $needle)) {
                return $label;
            }
        }

        return null;
    }

    protected function extractCategory(string $cleanText): string
    {
        $t = mb_strtolower($cleanText);
        if (preg_match('/\bselle|small saddle|show saddle|western saddle/i', $t)) return 'Selles & Accessoires';
        if (preg_match('/\bbridon|\bbride|\blicol|\bmous|\bfilet\b/i', $t)) return 'Bridons & Mors';
        if (preg_match('/\betrier|\betriviere|\betrar/i', $t)) return 'Étrivières et Étriers';
        if (preg_match('/hipposandal|hipposandale|guetre|molletiere|couverture|\btapis\b|protège|butternet|\bbagne|earbonnet/i', $t)) return 'Protection du cheval';
        if (preg_match('/casque|\bbotte|\bbottes|gilet|airbag|jacket|bomber|éperon|cravache|mini top|chaps/i', $t)) return 'Équipement du cavalier';
        if (preg_match('/\bhipposandal|hipposandale/i', $t)) return 'Protection du cheval';
        if (preg_match('/malle|armoire|tack box|coffre|sellerie mobile/i', $t)) return 'Selles & Accessoires';

        return 'Selles & Accessoires';
    }

    protected function extractPrice(string $caption): ?float
    {
        $t = str_replace("\xC2\xA0", ' ', $caption);
        $t = preg_replace('/[’"\x{202F}]/u', ' ', $t);

        $patterns = [
            '/prix\s*[:»\-]?\s*(?:de\s+)?([0-9][0-9\s.,]*)\s*(?:€|euros?|eur)/iu',
            '/price\s*[:»\-]?\s*€?\s*([0-9][0-9.,]*)/iu',
            '/([0-9][0-9\s.,]*)\s*(?:€|euros?|eur)/iu',
            '/€\s*([0-9][0-9.\s,]{1,12})/u',
            '/prix\s*[:»\-]?\s*([0-9][0-9\s.,]*)/iu',
            '/price\s*[:»\-]?\s*([0-9][0-9.,]+)/iu',
        ];

        foreach ($patterns as $pat) {
            if (preg_match($pat, $t, $m)) {
                $candidate = $this->toFloat($m[1]);
                if ($candidate && $candidate > 0) {
                    return $candidate;
                }
            }
        }
        return null;
    }

    protected function extractOldPrice(string $cleanText): ?float
    {
        $t = mb_strtolower(str_replace("\xC2\xA0", ' ', $cleanText));
        if (preg_match('/au lieu de\s*([0-9][0-9\s.,]*)/iu', $t, $m)
            || preg_match('/avant\s*[:»\-]?\s*([0-9][0-9\s.,]*)/iu', $t, $m)) {
            $v = $this->toFloat($m[1]);
            return $v && $v > 0 ? $v : null;
        }
        return null;
    }

    protected function extractSpecifications(string $cleanText): ?string
    {
        $labels = 'taille|arcade|coloris|couleur|année|année|seuil|quartier|coupleur|n°\s*de\s*série|numéro|siège|size|seat|flap|état|réf\b|ref\b';

        $specs = [];
        foreach ($this->lines($cleanText) as $line) {
            $l = mb_strtolower($line);
            if (preg_match('#^(' . $labels . ')[\s:/»•-]*#iu', $l, $m)) {
                $specs[] = trim($line);
            }
        }
        if (empty($specs)) return null;
        return implode('; ', array_slice($specs, 0, 10));
    }

    /**
     * Clean descriptive text: no emojis, hashtags, or contact/CTA lines.
     */
    protected function buildDescription(string $caption): string
    {
        $lines = $this->lines($this->clean($caption));
        $out = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;
            if ($this->isCtaOrBoilerplate($line)) continue;
            $out[] = $line;
        }
        $desc = implode("\n", $out);
        $desc = preg_replace('/\n{3,}/u', "\n\n", $desc);
        return trim($desc);
    }

    protected function isCtaOrBoilerplate(string $line): bool
    {
        $l = mb_strtolower($line);
        if (preg_match('/whatsapp|\bemail\b|gmail|sellerie.super|courteranges|25 rue|site web|siteweb|sellerie-super|prix\s*[:»]?\s*(à|0)|livraison possible|frais de port|contactez-?nous|dm |contact :|envoyons|expedition|voici une annonce|bok|ment collegé|a dire/i', $l)) {
            return true;
        }
        // pure hashtag-or-symbol line
        if (preg_match('/^#/u', trim($l))) return true;
        return false;
    }

    protected function lines(string $text): array
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $lines = preg_split('/\n+/u', $text);
        return $lines === false ? [] : $lines;
    }

    protected function toFloat(string $value): ?float
    {
        $value = preg_replace('/[^0-9.,]/', '', trim($value));
        if ($value === '') return null;

        $commas = substr_count($value, ',');
        $dots = substr_count($value, '.');

        if ($commas === 1 && $dots === 0) {
            $value = str_replace(',', '.', $value);
        } elseif ($dots > 1 || ($commas > 1)) {
            $value = str_replace([',', '.'], '', $value);
        } elseif ($dots === 1 && $commas >= 1) {
            // thousand separator then decimal: 1.234,56
            $value = str_replace('.', '', substr($value, 0, -3)) . str_replace(',', '.', substr($value, -3));
        }
        $value = (float) $value;
        return $value > 0 ? $value : null;
    }

    protected function isMostlyUpper(string $s): bool
    {
        $no = preg_replace('/\d/u', '', $s);
        $letters = preg_replace('/\W|\s/us', '', $no);
        if (mb_strlen($letters) < 4) return false;
        return mb_strtoupper($letters) === $letters;
    }
}