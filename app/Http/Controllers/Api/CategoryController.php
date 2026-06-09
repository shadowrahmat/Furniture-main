<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return response()->json($categories);
    }

    public function show(string $slug)
    {
        $category = Category::withCount('products')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($category);
    }

    public function featured()
    {
        $categories = Category::withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return response()->json($categories);
    }
}
