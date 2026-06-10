<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FurnitureType;

class FurnitureTypeController extends Controller
{
    public function index()
    {
        $furnitureTypes = FurnitureType::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return response()->json($furnitureTypes);
    }
}
