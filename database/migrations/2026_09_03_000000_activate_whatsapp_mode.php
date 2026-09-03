<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\SiteSetting;

return new class extends Migration
{
    public function up(): void
    {
        SiteSetting::setValue('order_mode', 'whatsapp');
        SiteSetting::setValue('whatsapp_number', '+33757753385');
    }

    public function down(): void
    {
        SiteSetting::setValue('order_mode', 'checkout');
    }
};
