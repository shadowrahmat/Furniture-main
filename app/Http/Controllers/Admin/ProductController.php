<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->status !== null) {
            $query->where('is_active', $request->status);
        }

        return response()->json($query->latest()->paginate(15));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'featured_image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
        ]);

        $data = $request->except(['featured_image', 'gallery', 'colors', 'tags', 'specifications', 'is_new']);
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);
        $data['sku'] = $request->sku ?: 'FS-' . strtoupper(Str::random(8));
        $data['colors'] = $request->colors ? explode(',', $request->colors) : null;
        $data['tags'] = $request->tags ? explode(',', $request->tags) : null;
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_best_seller'] = $request->boolean('is_best_seller');
        $data['is_new_arrival'] = $request->boolean('is_new');

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        $product = Product::create($data);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $i => $image) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $image->store('products', 'public'),
                    'sort_order' => $i,
                    'is_primary' => $i === 0,
                ]);
            }
        }

        return response()->json(['message' => 'Product created', 'product' => $product->load('images')], 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['category', 'images', 'reviews.user']));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'gallery.*' => 'nullable|image|max:5120',
        ]);

        $data = $request->except(['featured_image', 'gallery', '_method', 'is_new']);
        $data['colors'] = $request->colors ? explode(',', $request->colors) : null;
        $data['tags'] = $request->tags ? explode(',', $request->tags) : null;
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_best_seller'] = $request->boolean('is_best_seller');
        $data['is_new_arrival'] = $request->boolean('is_new');

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        $product->update($data);

        if ($request->hasFile('gallery')) {
            $existingCount = $product->images()->count();
            foreach ($request->file('gallery') as $i => $image) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $image->store('products', 'public'),
                    'sort_order' => $existingCount + $i,
                    'is_primary' => $existingCount === 0 && $i === 0,
                ]);
            }
        }

        return response()->json(['message' => 'Product updated', 'product' => $product->load('images')]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return response()->json(['message' => 'Status updated', 'is_active' => $product->is_active]);
    }
}
