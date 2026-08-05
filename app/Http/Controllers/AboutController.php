<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $stats = [
            (object) ['value' => '20+', 'label' => 'Années d\'expérience', 'icon' => 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5'],
            (object) ['value' => '5 000+', 'label' => 'Articles en stock', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
            (object) ['value' => '98%', 'label' => 'Clients satisfaits', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            (object) ['value' => '48h', 'label' => 'Expédition en France', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
        ];

        return view('pages.about', compact('stats'));
    }
}
