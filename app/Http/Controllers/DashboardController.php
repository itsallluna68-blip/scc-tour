<?php

namespace App\Http\Controllers;

use App\Models\TouristPlace;
use App\Models\Category;
use App\Models\VisitorCount;
use App\Models\Events;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // basic counts
        $touristCount = TouristPlace::where('status', 1)->count();
        $categoryCount = Category::where('status', 1)->count();

        // total visits sum
        $totalVisits = VisitorCount::sum('total_visitors');

        // events
        $totalEvents = Events::count();
        $upcomingEvents = Events::where('e_datetime', '>=', Carbon::now())->count();

        // recent accommodation placeholder: use latest 5 places count
        $recentAccommodation = TouristPlace::orderBy('id', 'desc')->take(5)->count();

        // visits chart labels/data
        $visits = VisitorCount::orderBy('vyear')->orderBy('vmonth')->get();
        $labels = $visits->map(function ($item) {
            return Carbon::create($item->vyear, $item->vmonth, 1)->format('F Y');
        });
        $data = $visits->pluck('total_visitors');

        // current month visits (for realtime card)
        $currentMonthVisits = VisitorCount::where('vyear', Carbon::now()->year)
            ->where('vmonth', Carbon::now()->month)
            ->value('total_visitors') ?? 0;

        // list of most recently added places (by id since timestamps disabled)
        $recentPlaces = TouristPlace::orderByDesc('id')->take(10)->get(['name']);

        return view('admin.admindashboard', compact(
            'touristCount',
            'categoryCount',
            'totalVisits',
            'totalEvents',
            'upcomingEvents',
            'recentAccommodation',
            'labels',
            'data',
            'currentMonthVisits',
            'recentPlaces'
        ));
    }

    /**
     * Return JSON for current month visits (used by realtime polling)
     */
    public function realtimeVisitors()
    {
        $count = VisitorCount::where('vyear', Carbon::now()->year)
            ->where('vmonth', Carbon::now()->month)
            ->value('total_visitors') ?? 0;

        return response()->json(['currentMonthVisits' => $count]);
    }

    /**
     * Public endpoint to record a single visit for the current month.
     * Can be called from the public landing page on load.
     */
    public function trackVisit(Request $request)
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        $visitor = VisitorCount::firstOrCreate(
            ['vyear' => $year, 'vmonth' => $month],
            ['total_visitors' => 0]
        );

        $visitor->increment('total_visitors');

        return response()->json(['currentMonthVisits' => $visitor->total_visitors]);
    }
}
