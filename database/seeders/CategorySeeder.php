<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Remeras',
                'description' => 'Remeras de todo tipo y estilo',
                'is_active' => true,
            ],
            [
                'name' => 'Pantalones',
                'description' => 'Pantalones y jeans',
                'is_active' => true,
            ],
            [
                'name' => 'Vestidos',
                'description' => 'Vestidos elegantes y casuales',
                'is_active' => true,
            ],
            [
                'name' => 'Buzos y Sweaters',
                'description' => 'Abrigos livianos',
                'is_active' => true,
            ],
            [
                'name' => 'Camperas',
                'description' => 'Camperas y abrigos',
                'is_active' => true,
            ],
            [
                'name' => 'Accesorios',
                'description' => 'Accesorios y complementos',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
