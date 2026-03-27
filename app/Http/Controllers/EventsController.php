<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Support\Carbon;

class EventsController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $firstEvent = Events::where('status', 1)
            ->where('e_datetime', '>=', $now)
            ->orderBy('e_datetime', 'asc')
            ->first();

        if (!$firstEvent) {
            return view('public.eventinfo', [
                'events' => collect(),
            ]);
        }

        $targetMonth = Carbon::parse($firstEvent->e_datetime);

        $events = Events::where('status', 1)
            ->whereMonth('e_datetime', $targetMonth->month)
            ->whereYear('e_datetime', $targetMonth->year)
            ->orderBy('e_datetime', 'asc')
            ->get()
            ->groupBy(function ($event) {
                return Carbon::parse($event->e_datetime)->format('F d, Y');
            });

        return view('public.eventinfo', compact('events'));
    }

    public function show($id)
    {
        $event = Events::where('status', 1)->findOrFail($id);

        $otherEvents = Events::where('status', 1)
            ->where('id', '!=', $id)
            ->where('e_datetime', '>=', Carbon::now())
            ->orderBy('e_datetime', 'asc')
            ->take(3)
            ->get();

        return view('public.eventshow', compact('event', 'otherEvents'));
    }
}