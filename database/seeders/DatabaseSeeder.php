<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@modacircular.com',
            'password' => bcrypt('admin123'),
        ]);

        // Ejecutar seeders
        $this->call([
            CategorySeeder::class,
            SettingSeeder::class,
        ]);
    }
}
