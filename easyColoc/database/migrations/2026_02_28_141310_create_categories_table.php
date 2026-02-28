<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Seed initial categories
        DB::table('categories')->insert([
            ['name' => 'Alimentation', 'slug' => 'alimentation', 'icon' => '🛒', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Loyer / Charges', 'slug' => 'loyer', 'icon' => '🏠', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Électricité', 'slug' => 'electricite', 'icon' => '⚡', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Eau', 'slug' => 'eau', 'icon' => '💧', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Internet', 'slug' => 'internet', 'icon' => '📡', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transport', 'slug' => 'transport', 'icon' => '🚗', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Autre', 'slug' => 'autre', 'icon' => '💰', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
