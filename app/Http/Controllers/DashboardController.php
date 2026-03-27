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
        $touristCount = TouristPlace::where('status', 1)->count();
        $categoryCount = Category::where('status', 1)->count();

        $totalEvents = Events::count();
        $upcomingEvents = Events::where('e_datetime', '>=', Carbon::now())->count();

        $recentAccommodation = TouristPlace::orderBy('id', 'desc')->take(5)->count();

        $vtype = $request->visitor_type;

        $totalVisitsQuery = VisitorCount::query();
        if ($vtype && $vtype !== 'all') {
            $totalVisitsQuery->where('visitor_type', $vtype);
        }
        $totalVisits = $totalVisitsQuery->sum('total_visitors');

        $totalResidents = VisitorCount::where('visitor_type', 'resident')->sum('total_visitors');
        $totalTourists = VisitorCount::where('visitor_type', 'visitor')->sum('total_visitors');

        $query = VisitorCount::query();
        if ($vtype && $vtype !== 'all') {
            $query->where('visitor_type', $vtype);
        }

        $visits = $query->selectRaw("
                vyear, 
                vmonth, 
                sum(total_visitors) as total,
                sum(case when visitor_type = 'resident' then total_visitors else 0 end) as resident_total,
                sum(case when visitor_type = 'visitor' then total_visitors else 0 end) as visitor_total
            ")
            ->groupBy('vyear', 'vmonth')
            ->orderBy('vyear')
            ->orderBy('vmonth')
            ->get();

        $labels = $visits->map(function ($item) {
            return Carbon::create($item->vyear, $item->vmonth, 1)->format('F Y');
        });
        $data = $visits->pluck('total');
        $residentData = $visits->pluck('resident_total');
        $visitorData = $visits->pluck('visitor_total');

        $currentMonthQuery = VisitorCount::where('vyear', Carbon::now()->year)
            ->where('vmonth', Carbon::now()->month);
        if ($vtype && $vtype !== 'all') {
            $currentMonthQuery->where('visitor_type', $vtype);
        }
        $currentMonthVisits = $currentMonthQuery->sum('total_visitors');

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
            'residentData',
            'visitorData',
            'currentMonthVisits',
            'recentPlaces',
            'totalResidents',
            'totalTourists'
        ));
    }

    public function realtimeVisitors(Request $request)
    {
        $query = VisitorCount::where('vyear', Carbon::now()->year)
            ->where('vmonth', Carbon::now()->month);

        if ($request->filled('visitor_type') && $request->visitor_type !== 'all') {
            $query->where('visitor_type', $request->visitor_type);
        }

        $count = $query->sum('total_visitors');

        $resCount = (clone $query)->where('visitor_type', 'resident')->sum('total_visitors');
        $visCount = (clone $query)->where('visitor_type', 'visitor')->sum('total_visitors');

        $lastUpdated = now()->timezone('Asia/Manila')->format('F d, Y \a\t h:i A');

        return response()->json([
            'currentMonthVisits' => (int) $count,
            'currentMonthResidents' => (int) $resCount,
            'currentMonthVisitors' => (int) $visCount,
            'lastUpdated' => $lastUpdated
        ]);
    }

    public function trackVisit(Request $request)
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        $visitor = VisitorCount::firstOrCreate(
            [
                'vyear' => $year,
                'vmonth' => $month,
                'visitor_type' => 'visitor',
                'loc' => 'website'
            ],
            ['total_visitors' => 0]
        );

        $visitor->increment('total_visitors');

        $totalMonth = VisitorCount::where('vyear', $year)
            ->where('vmonth', $month)
            ->sum('total_visitors');

        return response()->json(['currentMonthVisits' => (int) $totalMonth]);
    }
}