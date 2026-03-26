<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $guests = Guest::orderByDesc('created_at')->get();

        if ($request->wantsJson()) {
            return response()->json($guests);
        }

        return response()->json($guests);
    }

    public function export()
    {
        $guests = Guest::orderByDesc('created_at')->get();

        $csv = "Имя,Придёт,Еда,Алкоголь,Аллергия,Дата\n";
        foreach ($guests as $g) {
            $alcohol = implode(' | ', $g->alcohol_preferences ?? []);
            $csv .= "\"{$g->name}\",\"" . ($g->attending ? 'Да' : 'Нет') . "\",\"{$g->food_preference}\",\"{$alcohol}\",\"{$g->food_allergy}\",\"{$g->created_at}\"\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="guests.csv"',
        ]);
    }
}
