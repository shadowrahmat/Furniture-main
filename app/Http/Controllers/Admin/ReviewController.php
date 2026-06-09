<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user']);
        if ($request->status === 'pending') $query->where('is_approved', false);
        elseif ($request->status === 'approved') $query->where('is_approved', true);
        return response()->json($query->latest()->paginate(15));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        $product = $review->product;
        $avgRating = Review::where('product_id', $product->id)->where('is_approved', true)->avg('rating');
        $reviewCount = Review::where('product_id', $product->id)->where('is_approved', true)->count();
        $product->update(['avg_rating' => round($avgRating, 1), 'review_count' => $reviewCount]);

        return response()->json(['message' => 'Review approved']);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(['message' => 'Review deleted']);
    }
}
