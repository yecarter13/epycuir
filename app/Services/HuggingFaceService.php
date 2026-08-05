<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceService
{
    protected string $apiKey;
    protected string $chatModel;
    protected string $embeddingModel;
    protected ?string $lastFallback = null;

    public function __construct()
    {
        $this->apiKey = config('services.huggingface.api_key');
        $this->chatModel = config('services.huggingface.chat_model');
        $this->embeddingModel = config('services.huggingface.embedding_model');
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey)
            || !empty(config('services.gemini.api_key'))
            || !empty(config('services.groq.api_key'));
    }

    public function getLastFallback(): ?string
    {
        return $this->lastFallback;
    }

    public function chat(string $systemPrompt, string $userMessage, array $context = [], int $timeout = 15): string
    {
        $result = $this->chatWithGroq($systemPrompt, $userMessage, $context, $timeout);

        if ($result !== null) {
            return $result;
        }

        $result = $this->chatWithHuggingFace($systemPrompt, $userMessage, $context, $timeout);

        if ($result !== null) {
            return $result;
        }

        $result = $this->chatWithGemini($systemPrompt, $userMessage, $context, $timeout);

        if ($result !== null) {
            return $result;
        }

        $fallback = $this->fallbackResponse($userMessage);
        $this->lastFallback = $fallback;
        return $fallback;
    }

    public function parseSearchIntent(string $query, int $timeout = 5): array
    {
        $systemPrompt = "Vous êtes un assistant de recherche pour une sellerie équestre. Extrayez des paramètres de recherche structurés depuis la requête de l'utilisateur. "
            . "Répondez UNIQUEMENT avec un objet JSON valide. Utilisez ces clés : brand, category, make, model, min_price, max_price, keywords. "
            . "Example: {\"keywords\": \"body kit\", \"make\": \"Volkswagen\", \"model\": \"Golf 7\", \"max_price\": 500} "
            . "Traduisez les termes en français. Si une clé n'a pas de valeur, omettez-la. N'incluez AUCUN texte hors du JSON.";

        $response = $this->chat($systemPrompt, $query, [], $timeout);

        $response = trim($response);
        $response = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $response);
        $response = preg_replace('/^[\s\S]*?({.*?})[\s\S]*$/', '$1', $response);

        $params = json_decode($response, true);
        if (!is_array($params)) {
            return ['keywords' => $query];
        }

        if (isset($params['model'])) {
            $params['keywords'] = trim(($params['keywords'] ?? '') . ' ' . $params['model']);
            unset($params['model']);
        }

        return $params;
    }

    protected function chatWithHuggingFace(string $systemPrompt, string $userMessage, array $context = [], int $timeout = 15): ?string
    {
        if (!$this->isAvailable()) {
            return null;
        }

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        if (!empty($context)) {
            $contextStr = "Here are the products currently available:\n";
            foreach ($context as $i => $product) {
                $contextStr .= ($i + 1) . ". {$product['name']} - €{$product['price']} - {$product['brand']} - {$product['category']}\n";
            }
            $messages[] = ['role' => 'system', 'content' => $contextStr];
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        try {
            $payload = [
                'model' => $this->chatModel,
                'messages' => $messages,
                'max_tokens' => 500,
                'temperature' => 0.3,
            ];

            $response = Http::timeout($timeout)
                ->withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api-inference.huggingface.co/v1/chat/completions', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            if ($response->status() === 503) {
                $response = Http::timeout($timeout * 2)
                    ->withOptions(['verify' => false])
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->post("https://api-inference.huggingface.co/models/{$this->chatModel}", [
                        'inputs' => "<s>[INST] {$systemPrompt}\n\nUser: {$userMessage} [/INST]",
                        'parameters' => ['max_new_tokens' => 500, 'temperature' => 0.3],
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (is_array($data) && isset($data[0]['generated_text'])) {
                        $text = $data[0]['generated_text'];
                        $parts = explode('[/INST]', $text);
                        return trim(end($parts));
                    }
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::debug('HuggingFace chat exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    protected function chatWithGemini(string $systemPrompt, string $userMessage, array $context = [], int $timeout = 15): ?string
    {
        $geminiKey = config('services.gemini.api_key');
        if (!$geminiKey) return null;

        $contents = [['role' => 'user', 'parts' => [['text' => $systemPrompt . "\n\n" . $userMessage]]]];

        if (!empty($context)) {
            $contextStr = "Available products:\n";
            foreach ($context as $p) {
                $contextStr .= "- {$p['name']} (€{$p['price']}) by {$p['brand']}\n";
            }
            $contents[] = ['role' => 'model', 'parts' => [['text' => 'I see the following products are available.']]];
            $contents[] = ['role' => 'user', 'parts' => [['text' => $contextStr . "\n\nRecommend relevant products to: " . $userMessage]]];
        }

        try {
            $response = Http::timeout($timeout)
                ->withOptions(['verify' => false])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$geminiKey}", [
                    'contents' => $contents,
                    'generationConfig' => [
                        'maxOutputTokens' => 500,
                        'temperature' => 0.3,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }

            return null;

        } catch (\Exception $e) {
            Log::debug('Gemini chat exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function embed(string $text): array
    {
        if (!$this->isAvailable()) {
            return [];
        }

        $urls = [
            "https://router.huggingface.co/hf-inference/models/{$this->embeddingModel}",
            "https://api-inference.huggingface.co/models/{$this->embeddingModel}",
        ];

        foreach ($urls as $url) {
            try {
                $response = Http::timeout(15)
                    ->withOptions(['verify' => false])
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->post($url, ['inputs' => $text]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (is_array($data)) {
                        return $data;
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        Log::warning('HuggingFace embedding failed');
        return [];
    }

    protected function chatWithGroq(string $systemPrompt, string $userMessage, array $context = [], int $timeout = 15): ?string
    {
        $groqKey = config('services.groq.api_key');
        $model = config('services.groq.model');
        if (!$groqKey) return null;

        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        if (!empty($context)) {
            $contextStr = "Available products:\n";
            foreach ($context as $p) {
                $contextStr .= "- {$p['name']} (€{$p['price']}) by {$p['brand']}\n";
            }
            $messages[] = ['role' => 'system', 'content' => $contextStr];
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        try {
            $response = Http::timeout($timeout)
                ->withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $groqKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'max_tokens' => 500,
                    'temperature' => 0.3,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::debug('Groq API error', ['status' => $response->status(), 'body' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::debug('Groq chat exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function cosineSimilarity(array $vec1, array $vec2): float
    {
        if (empty($vec1) || empty($vec2)) return 0;
        $dot = 0; $norm1 = 0; $norm2 = 0;
        $count = min(count($vec1), count($vec2));
        for ($i = 0; $i < $count; $i++) {
            $dot += $vec1[$i] * $vec2[$i];
            $norm1 += $vec1[$i] ** 2;
            $norm2 += $vec2[$i] ** 2;
        }
        $denom = sqrt($norm1) * sqrt($norm2);
        return $denom > 0 ? $dot / $denom : 0;
    }

    protected function fallbackResponse(string $userMessage): string
    {
        return "Aucun article trouvé pour votre recherche. Essayez d'autres mots-clés comme \"brake discs Golf 7\" or \"headlight Peugeot 208\". "
            . "Vous pouvez aussi parcourir nos catégories ou utiliser la barre de recherche.";
    }
}
