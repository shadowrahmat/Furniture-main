<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomFurnitureInquiry;
use Illuminate\Http\Request;

class CustomInquiryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'furniture_type' => 'required|string',
            'description' => 'required|string',
            'space_type' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'material_preference' => 'nullable|string',
            'color_preference' => 'nullable|string',
            'budget_min' => 'nullable|numeric',
            'budget_max' => 'nullable|numeric',
            'reference_image' => 'nullable|image|max:5120',
        ]);

        $data = $request->except('reference_image');

        if ($request->hasFile('reference_image')) {
            $data['reference_image'] = $request->file('reference_image')->store('inquiries', 'public');
        }

        CustomFurnitureInquiry::create($data);

        return response()->json(['message' => 'Inquiry submitted successfully. Our team will contact you within 24 hours.'], 201);
    }
}
