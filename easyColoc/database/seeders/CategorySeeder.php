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
            ['name' => 'Alimentation', 'icon' => '🛒'],
            ['name' => 'Loyer / Charges', 'icon' => '🏠'],
            ['name' => 'Électricité', 'icon' => '⚡'],
            ['name' => 'Eau', 'icon' => '💧'],
            ['name' => 'Internet', 'icon' => '📡'],
            ['name' => 'Transport', 'icon' => '🚗'],
            ['name' => 'Autre', 'icon' => '💰'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::updateOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
