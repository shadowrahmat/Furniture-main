<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        return response()->json(Banner::orderBy('sort_order')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = $request->except('image');
        $data['image'] = $request->file('image')->store('banners', 'public');

        $banner = Banner::create($data);
        return response()->json(['message' => 'Banner created', 'banner' => $banner], 201);
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->except(['image', '_method']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);
        return response()->json(['message' => 'Banner updated', 'banner' => $banner]);
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return response()->json(['message' => 'Banner deleted']);
    }
}
