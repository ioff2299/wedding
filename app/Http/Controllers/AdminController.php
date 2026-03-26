<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\SiteBlock;
use App\Support\WeddingFormDefaults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $order = array_flip([
            'hero',
            'between_text',
            'location',
            'timing',
            'dress_code',
            'details',
            'form',
        ]);

        $blocks = SiteBlock::all()
            ->sortBy(fn(SiteBlock $block) => $order[$block->key] ?? 999)
            ->values()
            ->map(function (SiteBlock $block) {
            if ($block->key === 'form') {
                $c = $block->content ?? [];
                $def = WeddingFormDefaults::formOptions();
                $opts = is_array($c['form_options'] ?? null) ? $c['form_options'] : [];
                $c['form_options'] = [
                    'attending' => !empty($opts['attending']) ? $opts['attending'] : $def['attending'],
                    'food'      => !empty($opts['food']) ? $opts['food'] : $def['food'],
                    'alcohol'   => !empty($opts['alcohol']) ? $opts['alcohol'] : $def['alcohol'],
                ];
                $c['question_labels'] = array_merge(
                    WeddingFormDefaults::questionLabels(),
                    is_array($c['question_labels'] ?? null) ? $c['question_labels'] : []
                );
                $block->content = $c;
            }

            return $block;
        });
        $guests = Guest::orderByDesc('created_at')->get();
        $stats = [
            'total'     => $guests->count(),
            'attending' => $guests->where('attending', true)->count(),
            'declined'  => $guests->where('attending', false)->count(),
        ];

        return view('admin.index', compact('blocks', 'guests', 'stats'));
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.index');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.index'));
        }

        return back()->withErrors(['email' => 'Неверный логин или пароль.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
