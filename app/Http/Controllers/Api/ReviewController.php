<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, int $productId)
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);

        $review = Review::updateOrCreate(
            ['product_id' => $productId, 'user_id' => $request->user()->id],
            [
                'rating' => $request->rating,
                'title' => $request->title,
                'body' => $request->body,
                'is_approved' => false,
            ]
        );

        return response()->json(['message' => 'Review submitted for approval', 'review' => $review], 201);
    }
}
