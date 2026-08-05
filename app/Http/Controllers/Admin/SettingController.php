<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $fields = [
            'order_mode', 'whatsapp_number', 'phone', 'email', 'address',
            'opening_hours', 'facebook_url', 'instagram_url',
            'tiktok_url', 'twitter_url', 'hero_banner_1',
            'hero_banner_2', 'hero_banner_3',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                SiteSetting::setValue($field, $request->input($field));
            }
        }

        return back()->with('success', 'Paramètres mis à jour avec succès.');
    }
}
