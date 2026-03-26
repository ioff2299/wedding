<?php

namespace App\Http\Controllers;

use App\Services\GeocodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeocodeController extends Controller
{
    public function lookup(Request $request, GeocodeService $geocode): JsonResponse
    {
        $data = $request->validate([
            'address' => 'required|string|max:500',
        ]);

        $coords = $geocode->resolve($data['address']);

        if ($coords === null) {
            return response()->json([
                'message' => 'Не удалось определить координаты по адресу.',
            ], 422);
        }

        return response()->json([
            'lat' => $coords['lat'],
            'lng' => $coords['lng'],
        ]);
    }
}
