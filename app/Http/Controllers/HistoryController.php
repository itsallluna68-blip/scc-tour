<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Exploreplaces;

class HistoryController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('details', 'term')->toArray();

        if (isset($settings['historyImg'])) {
            $settings['historyImg'] = json_decode($settings['historyImg'], true);
        }

        if (isset($settings['bgImg'])) {
            $settings['bgImg'] = json_decode($settings['bgImg'], true);
        }

        $popularPlaces = Exploreplaces::where('is_popular', 1)->get();

        return view('public.longinfo.historypage', compact('settings', 'popularPlaces'));
    }
}