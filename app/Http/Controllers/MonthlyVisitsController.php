<?php

namespace App\Http\Controllers;

use App\Models\VisitorCount;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonthlyVisitsController extends Controller
{
    public function index(Request $request)
    {
        $query = VisitorCount::orderBy('vyear')
            ->orderBy('vmonth');

        // filter by location, respecting the "all" option from the view
        if ($request->filled('location') && $request->location !== 'all') {
            $query->where('loc', $request->location);
        }

        if ($request->filled('year') && $request->year != 'all') {
            $query->where('vyear', $request->year);
        }

        if ($request->filled('month') && $request->month != 'all') {
            $query->where('vmonth', $request->month);
        }

        $visits = $query->get();

        $labels = $visits->map(function ($item) {
            return Carbon::create($item->vyear, $item->vmonth, 1)
                ->format('F Y');
        });

        $data = $visits->pluck('total_visitors');

        // keep the year dropdown in sync with whatever location filter is
        // currently applied.  if the user narrowed by location, only include
        // years that actually have data for that location.
        $yearsQuery = VisitorCount::select('vyear')->distinct();
        if ($request->filled('location') && $request->location !== 'all') {
            $yearsQuery->where('loc', $request->location);
        }
        $years = $yearsQuery->orderBy('vyear', 'DESC')->pluck('vyear');

        return view('admin.list.monthlyvisits', compact('labels', 'data', 'years'));
    }



    public function overview(Request $request)
{
    $search = $request->search;
    $month  = $request->month;
    $year   = $request->year;

    $query = VisitorCount::query();

    // Search (by month name or year)
   // Search
    if ($request->search) {
        $query->where(function($q) use ($request) {
            $q->where('vmonth', 'like', "%{$request->search}%")
              ->orWhere('vyear', 'like', "%{$request->search}%");
        });
    }

    // Filter by month
    if ($month && $month !== 'all') {
        $query->where('vmonth', $month);
    }

    // Filter by year
    if ($year && $year !== 'all') {
        $query->where('vyear', $year);
    }

    $mvisits = $query->orderBy('vyear', 'desc')
                     ->orderBy('vmonth', 'asc')
                     ->get();

    $years = VisitorCount::select('vyear')
                ->distinct()
                ->orderBy('vyear', 'desc')
                ->pluck('vyear');

    return view('admin.list.monthlyvisitsoverview', compact('mvisits', 'years'));
}

    public function store(Request $request)
    {
        // Validate inputs.  We now require the location field since the
        // underlying table has a non-nullable `loc` column.
        $request->validate([
            'vdate' => 'required|date',
            'vcounts' => 'required|integer|min:0',
            'location' => 'required|string',
        ]);

        // Parse the month input (YYYY-MM)
        $date = Carbon::parse($request->vdate);

        VisitorCount::create([
            'vmonth' => $date->month,        // 1-12
            'vyear' => $date->year,          // e.g., 2026
            'total_visitors' => $request->vcounts,
            'loc' => $request->location,
            'date_add' => now(),
        ]);

        return redirect()->route('monthlyvisits.overview')
            ->with('success', 'Monthly visit added successfully.');
    }
    public function edit($id)
    {
        $visit = VisitorCount::findOrFail($id);
        return response()->json($visit);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'total_visitors' => 'required|integer|min:0',
            'location' => 'sometimes|string',
        ]);

        $visit = VisitorCount::findOrFail($id);

        if ($request->filled('location')) {
            $visit->loc = $request->location;
        }
        $visit->total_visitors = $request->total_visitors;
        $visit->save();

        return redirect()->route('monthlyvisits.overview')
            ->with('success', 'Monthly visit record updated successfully.');
    }

    public function destroy($id)
    {
        $visit = VisitorCount::findOrFail($id);
        $visit->delete();

        return redirect()->route('monthlyvisits.overview')
            ->with('success', 'Record deleted successfully.');
    }
}
