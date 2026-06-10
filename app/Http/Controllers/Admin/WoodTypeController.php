<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WoodType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WoodTypeController extends Controller
{
    public function index()
    {
        return response()->json(WoodType::orderBy('display_order')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'price_adjustment_type' => 'nullable|in:fixed,percentage',
            'price_adjustment_value' => 'nullable|numeric',
            'availability' => 'nullable|in:in_stock,limited,out_of_stock,made_to_order',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('wood-types', 'public');
        }

        $woodType = WoodType::create($data);
        return response()->json($woodType, 201);
    }

    public function update(Request $request, WoodType $woodType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'price_adjustment_type' => 'nullable|in:fixed,percentage',
            'price_adjustment_value' => 'nullable|numeric',
            'availability' => 'nullable|in:in_stock,limited,out_of_stock,made_to_order',
        ]);

        $data = $request->except(['image', '_method']);

        if (!empty($data['slug'])) $data['slug'] = Str::slug($data['slug']);
        else $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('wood-types', 'public');
        }

        $woodType->update($data);
        return response()->json($woodType);
    }

    public function destroy(WoodType $woodType)
    {
        $woodType->delete();
        return response()->json(['message' => 'Wood type deleted']);
    }
}
