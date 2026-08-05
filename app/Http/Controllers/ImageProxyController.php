<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageProxyController extends Controller
{
    protected array $placeholderPatterns = [
        '/no-image',
        '/noimage',
        '/placeholder',
        '/na_',
        '/NA_',
        '/default.',
        '/not-available',
    ];

    public function proxy(Request $request)
    {
        $url = $request->query('url');

        if (!$url || !str_starts_with($url, 'http')) {
            return $this->fallback();
        }

        $path = parse_url($url, PHP_URL_PATH) ?? '';
        foreach ($this->placeholderPatterns as $pattern) {
            if (str_contains($path, $pattern)) {
                return $this->fallback();
            }
        }

        $response = Http::withoutVerifying()->withHeaders([
            'Referer' => 'https://autopartsway.com/',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ])->timeout(15)->get($url);

        if (!$response->successful() || strlen($response->body()) < 2000) {
            return $this->fallback();
        }

        $contentType = $response->header('Content-Type');
        if (!str_starts_with($contentType, 'image/')) {
            $contentType = 'image/jpeg';
        }

        return response($response->body(), 200, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'public, max-age=86400',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }

    protected function fallback()
    {
        $svgPath = base_path('public/images/default.svg');
        if (file_exists($svgPath)) {
            return response(file_get_contents($svgPath), 200, [
                'Content-Type' => 'image/svg+xml',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
        $jpgPath = base_path('public/images/default.jpg');
        if (file_exists($jpgPath)) {
            return response(file_get_contents($jpgPath), 200, [
                'Content-Type' => 'image/jpeg',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
        $pngPath = base_path('public/images/default.png');
        if (file_exists($pngPath)) {
            return response(file_get_contents($pngPath), 200, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
        return response('', 404);
    }
}
