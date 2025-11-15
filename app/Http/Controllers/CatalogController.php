<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Mostrar página principal con productos destacados
     */
    public function index()
    {
        $featuredProducts = Product::with(['category', 'primaryImage'])
            ->featured()
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount(['activeProducts'])
            ->having('active_products_count', '>', 0)
            ->get();

        return view('catalog.index', compact('featuredProducts', 'categories'));
    }

    /**
     * Mostrar todos los productos con filtros
     */
    public function products(Request $request)
    {
        $query = Product::with(['category', 'primaryImage'])->visible();

        // Filtro por categoría
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filtro por búsqueda
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Ordenamiento
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);

        $categories = Category::where('is_active', true)
            ->withCount(['activeProducts'])
            ->having('active_products_count', '>', 0)
            ->get();

        return view('catalog.products', compact('products', 'categories'));
    }

    /**
     * Mostrar detalle de un producto
     */
    public function show(Product $product)
    {
        // Verificar que el producto esté visible y disponible
        if (!$product->isAvailable()) {
            abort(404);
        }

        $product->load(['category', 'images']);

        // Productos relacionados de la misma categoría
        $relatedProducts = Product::with(['category', 'primaryImage'])
            ->visible()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }

    /**
     * Mostrar productos de una categoría
     */
    public function category(Category $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $products = Product::with(['category', 'primaryImage'])
            ->visible()
            ->where('category_id', $category->id)
            ->paginate(12);

        $categories = Category::where('is_active', true)
            ->withCount(['activeProducts'])
            ->having('active_products_count', '>', 0)
            ->get();

        return view('catalog.category', compact('category', 'products', 'categories'));
    }
}
