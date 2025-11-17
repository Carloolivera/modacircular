<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        // URLs de imágenes de ejemplo (Unsplash)
        $imageUrls = [
            'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=800',
            'https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?w=800',
            'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800',
            'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=800',
            'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800',
            'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=800',
            'https://images.unsplash.com/photo-1564584217132-2271feaeb3c5?w=800',
            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800',
        ];

        $products = Product::all();

        foreach ($products as $index => $product) {
            // Usar imagen según índice, rotando si hay más productos que imágenes
            $imageUrl = $imageUrls[$index % count($imageUrls)];
            
            try {
                // Descargar imagen
                $response = Http::timeout(10)->get($imageUrl);
                
                if ($response->successful()) {
                    $filename = 'product-' . $product->id . '-' . time() . '.jpg';
                    $path = 'products/' . $filename;
                    
                    // Guardar en storage
                    Storage::disk('public')->put($path, $response->body());
                    
                    // Crear registro en BD
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $path,
                        'is_primary' => true,
                    ]);
                    
                    $this->command->info("✅ Imagen agregada a: {$product->name}");
                }
            } catch (\Exception $e) {
                $this->command->warn("⚠️  Error al descargar imagen para: {$product->name}");
            }
        }
    }
}