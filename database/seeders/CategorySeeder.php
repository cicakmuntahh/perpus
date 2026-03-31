<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiksi', 'icon' => '📚'],
            ['name' => 'Sejarah', 'icon' => '📖'],
            ['name' => 'Inspiratif', 'icon' => '💡'],
            ['name' => 'Roman', 'icon' => '❤️'],
            ['name' => 'Fiksi Sains', 'icon' => '🔬'],
            ['name' => 'Pendidikan', 'icon' => '🎓'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
