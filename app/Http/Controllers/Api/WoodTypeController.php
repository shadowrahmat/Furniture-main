<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WoodType;

class WoodTypeController extends Controller
{
    public function index()
    {
        $woodTypes = WoodType::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return response()->json($woodTypes);
    }
}
