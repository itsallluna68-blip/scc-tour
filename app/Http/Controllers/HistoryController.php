<?php

namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Http\Request;

class HistoryController extends Controller
{ 
      public function index()
    {
        $settings = Setting::pluck('details', 'term')->toArray();

        // decode any json-stored arrays
        if (isset($settings['historyImg'])) {
            $settings['historyImg'] = json_decode($settings['historyImg'], true);
        }

        if (isset($settings['bgImg'])) {
            $settings['bgImg'] = json_decode($settings['bgImg'], true);
        }

        return view('public.longinfo.historypage', compact('settings'));
    }
}
