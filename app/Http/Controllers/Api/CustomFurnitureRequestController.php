<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomFurnitureRequest;
use App\Models\CustomRequestFile;
use App\Models\FurnitureType;
use App\Models\WoodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;

class CustomFurnitureRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'furniture_type_id' => 'required|integer|exists:furniture_types,id',
            'wood_type_id' => 'required|integer|exists:wood_types,id',

            'length' => 'required|numeric|min:0.01|max:99999.99',
            'width' => 'required|numeric|min:0.01|max:99999.99',
            'height' => 'required|numeric|min:0.01|max:99999.99',
            'dimension_unit' => 'required|in:cm,mm,inch,feet',
            'quantity' => 'required|integer|min:1|max:100',

            'finish' => 'nullable|string|max:255',
            'color_preference' => 'nullable|string|max:255',
            'design_style' => 'nullable|string|max:255',
            'upholstery_fabric' => 'nullable|string|max:255',

            'budget_min' => 'nullable|numeric|min:0|max:99999999.99',
            'budget_max' => 'nullable|numeric|min:0|max:99999999.99|gte:budget_min',
            'delivery_date' => 'nullable|date|after_or_equal:today',
            'delivery_address' => 'nullable|string|max:1000',
            'delivery_preference' => 'nullable|string|max:255',
            'additional_notes' => 'nullable|string|max:2000',

            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'customer_address' => 'nullable|string|max:1000',
            'terms_agreed' => 'required|accepted',

            'files' => 'nullable|array|max:5',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        $furnitureType = FurnitureType::find($validated['furniture_type_id']);
        if (!$furnitureType->is_active) {
            return response()->json([
                'message' => 'The selected furniture type is no longer available. Please choose another.',
            ], 422);
        }

        $woodType = WoodType::find($validated['wood_type_id']);
        if (!$woodType->is_active || $woodType->availability === 'out_of_stock') {
            return response()->json([
                'message' => 'The selected wood type is no longer available. Please choose another.',
            ], 422);
        }

        $user = $this->resolveUser($request);
        $storedPaths = [];

        try {
            $customRequest = DB::transaction(function () use ($validated, $request, $user, &$storedPaths) {
                $customRequest = CustomFurnitureRequest::create([
                    'request_number' => CustomFurnitureRequest::generateRequestNumber(),
                    'user_id' => $user?->id,
                    'furniture_type_id' => $validated['furniture_type_id'],
                    'wood_type_id' => $validated['wood_type_id'],
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'customer_address' => $validated['customer_address'] ?? null,
                    'length' => $validated['length'],
                    'width' => $validated['width'],
                    'height' => $validated['height'],
                    'dimension_unit' => $validated['dimension_unit'],
                    'quantity' => $validated['quantity'],
                    'finish' => $validated['finish'] ?? null,
                    'color_preference' => $validated['color_preference'] ?? null,
                    'design_style' => $validated['design_style'] ?? null,
                    'upholstery_fabric' => $validated['upholstery_fabric'] ?? null,
                    'budget_min' => $validated['budget_min'] ?? null,
                    'budget_max' => $validated['budget_max'] ?? null,
                    'delivery_preference' => $validated['delivery_preference'] ?? null,
                    'delivery_date' => $validated['delivery_date'] ?? null,
                    'delivery_address' => $validated['delivery_address'] ?? null,
                    'additional_notes' => $validated['additional_notes'] ?? null,
                    'status' => 'pending',
                ]);

                foreach ($request->file('files', []) as $file) {
                    $path = $file->store('custom-furniture-requests', 'public');
                    $storedPaths[] = $path;

                    CustomRequestFile::create([
                        'custom_furniture_request_id' => $customRequest->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => 'customer',
                    ]);
                }

                return $customRequest;
            });
        } catch (\Throwable $e) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'message' => 'We could not submit your request right now. Please try again in a moment.',
            ], 500);
        }

        $customRequest->load(['furnitureType', 'woodType']);

        return response()->json([
            'message' => 'Your custom furniture request has been submitted successfully.',
            'request_number' => $customRequest->request_number,
            'data' => [
                'request_number' => $customRequest->request_number,
                'status' => $customRequest->status,
                'status_label' => $customRequest->status_label,
                'furniture_type' => $customRequest->furnitureType?->name,
                'wood_type' => $customRequest->woodType?->name,
                'quantity' => $customRequest->quantity,
                'submitted_at' => $customRequest->created_at->toIso8601String(),
            ],
        ], 201);
    }

    private function resolveUser(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return null;
        }

        $accessToken = PersonalAccessToken::findToken($token);

        return $accessToken?->tokenable;
    }
}
