<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm,ogg|max:20480',
            'folder' => 'nullable|string|in:products,banners,descriptions',
        ]);

        $folder = $request->input('folder', 'descriptions');
        $name = Str::random(30) . '.' . $request->file('file')->extension();
        $request->file('file')->move(base_path('public/images/' . $folder), $name);

        return response()->json([
            'url' => asset('images/' . $folder . '/' . $name),
            'path' => $folder . '/' . $name,
            'name' => $name,
        ]);
    }

    public function browser()
    {
        $files = [];
        foreach (['products', 'banners', 'descriptions'] as $folder) {
            $dir = base_path('public/images/' . $folder);
            if (is_dir($dir)) {
                $items = array_diff(scandir($dir), ['.', '..']);
                foreach ($items as $name) {
                    $path = $folder . '/' . $name;
                    $fullPath = $dir . '/' . $name;
                    $files[] = [
                        'url' => asset('images/' . $path),
                        'path' => $path,
                        'name' => $name,
                        'folder' => $folder,
                        'size' => filesize($fullPath),
                        'modified' => filemtime($fullPath),
                    ];
                }
            }
        }

        return response()->json($files);
    }
}
