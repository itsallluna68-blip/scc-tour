<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\Exploreplaces;
use Carbon\Carbon;

class publichomeController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Get next 3 upcoming events
        $events = Events::where('e_datetime', '>=', $now)
            ->orderBy('e_datetime', 'asc')
            ->take(5)
            ->get();

        $popularPlaces = Exploreplaces::where('status', 1)
            ->where('is_popular', 1)
            ->orderBy('id', 'asc')
            ->get();

        // load settings for history image/text
        $settings = \App\Models\Setting::pluck('details', 'term')->toArray();
        if (isset($settings['historyImg'])) {
            $settings['historyImg'] = json_decode($settings['historyImg'], true);
        }

        return view('public.publichome', compact('events', 'popularPlaces', 'settings'));
    }

}
