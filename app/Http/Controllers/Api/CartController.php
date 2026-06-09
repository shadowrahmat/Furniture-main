<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getKey(Request $request): array
    {
        return $request->user()
            ? ['user_id' => $request->user()->id]
            : ['session_id' => $request->session()->getId()];
    }

    public function index(Request $request)
    {
        $items = Cart::with(['product.images', 'product.category'])
            ->where($this->getKey($request))
            ->get();

        $subtotal = $items->sum(fn($item) => $item->price * $item->quantity);

        return response()->json([
            'items' => $items,
            'subtotal' => $subtotal,
            'count' => $items->sum('quantity'),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'color' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['message' => 'Insufficient stock'], 422);
        }

        $key = $this->getKey($request);
        $existing = Cart::where($key)
            ->where('product_id', $request->product_id)
            ->where('color', $request->color)
            ->first();

        if ($existing) {
            $newQty = $existing->quantity + $request->quantity;
            if ($product->stock < $newQty) {
                return response()->json(['message' => 'Insufficient stock'], 422);
            }
            $existing->update(['quantity' => $newQty]);
            $cart = $existing;
        } else {
            $cart = Cart::create(array_merge($key, [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'color' => $request->color,
                'price' => $product->sale_price ?? $product->price,
            ]));
        }

        return response()->json(['message' => 'Added to cart', 'cart' => $cart->load('product.images')], 201);
    }

    public function update(Request $request, Cart $cart)
    {
        $this->authorize_cart($request, $cart);

        $request->validate(['quantity' => 'required|integer|min:1']);

        if ($cart->product->stock < $request->quantity) {
            return response()->json(['message' => 'Insufficient stock'], 422);
        }

        $cart->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated', 'cart' => $cart]);
    }

    public function remove(Request $request, Cart $cart)
    {
        $this->authorize_cart($request, $cart);
        $cart->delete();
        return response()->json(['message' => 'Item removed from cart']);
    }

    public function clear(Request $request)
    {
        Cart::where($this->getKey($request))->delete();
        return response()->json(['message' => 'Cart cleared']);
    }

    private function authorize_cart(Request $request, Cart $cart): void
    {
        $key = $this->getKey($request);
        $field = array_key_first($key);
        abort_if($cart->$field != $key[$field], 403, 'Unauthorized');
    }
}
