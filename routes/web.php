<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

// Rutas del catálogo público
Route::get('/', [CatalogController::class, 'index'])->name('home');
Route::get('/productos', [CatalogController::class, 'products'])->name('products.index');
Route::get('/producto/{product:slug}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/categoria/{category:slug}', [CatalogController::class, 'category'])->name('category.show');

// Rutas del carrito
Route::prefix('carrito')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/agregar/{product}', [CartController::class, 'add'])->name('add');
    Route::patch('/actualizar/{productId}', [CartController::class, 'update'])->name('update');
    Route::delete('/eliminar/{productId}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/vaciar', [CartController::class, 'clear'])->name('clear');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/whatsapp', [CartController::class, 'sendToWhatsApp'])->name('whatsapp');
});
