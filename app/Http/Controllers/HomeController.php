<?php

namespace App\Http\Controllers;

use App\Models\SiteBlock;
use App\Support\WeddingFormDefaults;

class HomeController extends Controller
{
    public function index()
    {
        $blocks = SiteBlock::getAllKeyed();

        $form = $blocks['form'] ?? [];
        $defOpt = WeddingFormDefaults::formOptions();
        $opts = is_array($form['form_options'] ?? null) ? $form['form_options'] : [];
        $form['form_options'] = [
            'attending' => !empty($opts['attending']) ? $opts['attending'] : $defOpt['attending'],
            'food'      => !empty($opts['food']) ? $opts['food'] : $defOpt['food'],
            'alcohol'   => !empty($opts['alcohol']) ? $opts['alcohol'] : $defOpt['alcohol'],
        ];
        $form['question_labels'] = array_merge(
            WeddingFormDefaults::questionLabels(),
            is_array($form['question_labels'] ?? null) ? $form['question_labels'] : []
        );
        $blocks['form'] = $form;

        return view('welcome', compact('blocks'));
    }
}
