<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        DB::table('site_settings')->insert([
            ['key' => 'whatsapp_number', 'value' => '33756965789'],
            ['key' => 'phone', 'value' => '+33 7 56 96 57 89'],
            ['key' => 'email', 'value' => 'info@scelle.com'],
            ['key' => 'address', 'value' => '25 rue de Cogiandant Crénière, 10270 Courteranges'],
            ['key' => 'facebook_url', 'value' => '#'],
            ['key' => 'instagram_url', 'value' => '#'],
            ['key' => 'tiktok_url', 'value' => '#'],
            ['key' => 'twitter_url', 'value' => '#'],
            ['key' => 'opening_hours', 'value' => 'Lun-Ven : 9h00 - 18h00'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
