<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE products ADD FULLTEXT ft_search (name, sku, description, compatibility, brand)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE products DROP INDEX ft_search');
    }
};
