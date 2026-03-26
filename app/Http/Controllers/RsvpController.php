<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    public function show(string $token)
    {
        $guest = Guest::where('user_token', $token)->first();

        if (!$guest) {
            return response()->json(null);
        }

        return response()->json($guest);
    }

    public function store(Request $request)
    {
        $token = $request->input('user_token') ?? $request->input('token');
        $request->merge(['user_token' => $token]);

        $validated = $request->validate([
            'user_token' => 'required|string|max:64',
            'name'       => 'required|string|max:255',
            'attending'  => 'required|boolean',
            'food_preference'     => 'nullable|string|max:120',
            'alcohol_preferences' => 'nullable|array',
            'alcohol_preferences.*' => 'nullable|string|max:120',
            'food_allergy'        => 'nullable|string|max:500',
        ]);

        $guest = Guest::updateOrCreate(
            ['user_token' => $validated['user_token']],
            $validated
        );

        return response()->json(['success' => true, 'guest' => $guest]);
    }
}
