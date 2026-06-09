<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images'])
            ->where('is_active', true);

        if ($request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->min_price) {
            $query->where(fn($q) => $q->where('sale_price', '>=', $request->min_price)->orWhere('price', '>=', $request->min_price));
        }

        if ($request->max_price) {
            $query->where(fn($q) => $q->where('sale_price', '<=', $request->max_price)->orWhere('price', '<=', $request->max_price));
        }

        if ($request->featured) $query->where('is_featured', true);
        if ($request->new_arrival) $query->where('is_new_arrival', true);
        if ($request->best_seller) $query->where('is_best_seller', true);

        $sortBy = $request->sort ?? 'newest';
        match($sortBy) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
            'popular' => $query->orderBy('views', 'desc'),
            'rating' => $query->orderBy('avg_rating', 'desc'),
            default => $query->latest(),
        };

        $products = $query->paginate($request->per_page ?? 12);

        return response()->json($products);
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'images', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $product->increment('views');

        $related = Product::with(['category', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return response()->json([
            'product' => $product,
            'related' => $related,
        ]);
    }

    public function featured()
    {
        $products = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(8)
            ->get();

        return response()->json($products);
    }

    public function bestSellers()
    {
        $products = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->where('is_best_seller', true)
            ->orderBy('views', 'desc')
            ->limit(8)
            ->get();

        return response()->json($products);
    }

    public function newArrivals()
    {
        $products = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->where('is_new_arrival', true)
            ->latest()
            ->limit(8)
            ->get();

        return response()->json($products);
    }
}
