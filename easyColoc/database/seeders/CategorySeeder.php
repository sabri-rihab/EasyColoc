<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Alimentation', 'slug' => 'alimentation', 'icon' => '🛒'],
            ['name' => 'Loyer / Charges', 'slug' => 'loyer', 'icon' => '🏠'],
            ['name' => 'Électricité', 'slug' => 'electricite', 'icon' => '⚡'],
            ['name' => 'Eau', 'slug' => 'eau', 'icon' => '💧'],
            ['name' => 'Internet', 'slug' => 'internet', 'icon' => '📡'],
            ['name' => 'Transport', 'slug' => 'transport', 'icon' => '🚗'],
            ['name' => 'Autre', 'slug' => 'autre', 'icon' => '💰'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
