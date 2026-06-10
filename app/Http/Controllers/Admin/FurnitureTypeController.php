<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FurnitureType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FurnitureTypeController extends Controller
{
    public function index()
    {
        return response()->json(FurnitureType::orderBy('display_order')->get());
    }

    public function store(Request $request)
    {
        if ($request->starting_price === '') {
            $request->merge(['starting_price' => null]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'starting_price' => 'nullable|numeric',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('furniture-types', 'public');
        }

        $furnitureType = FurnitureType::create($data);
        return response()->json($furnitureType, 201);
    }

    public function update(Request $request, FurnitureType $furnitureType)
    {
        if ($request->starting_price === '') {
            $request->merge(['starting_price' => null]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'starting_price' => 'nullable|numeric',
        ]);

        $data = $request->except(['image', '_method']);

        if (!empty($data['slug'])) $data['slug'] = Str::slug($data['slug']);
        else $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('furniture-types', 'public');
        }

        $furnitureType->update($data);
        return response()->json($furnitureType);
    }

    public function destroy(FurnitureType $furnitureType)
    {
        $furnitureType->delete();
        return response()->json(['message' => 'Furniture type deleted']);
    }
}
