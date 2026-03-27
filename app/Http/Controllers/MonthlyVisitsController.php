<?php

namespace App\Http\Controllers;

use App\Models\VisitorCount;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonthlyVisitsController extends Controller
{
    public function index(Request $request)
    {
        $query = VisitorCount::query();

        if ($request->filled('location') && $request->location !== 'all') {
            $query->where('loc', 'like', "%{$request->location}%");
        }

        if ($request->filled('year') && $request->year != 'all') {
            $query->where('vyear', $request->year);
        }

        if ($request->filled('month') && $request->month != 'all') {
            $query->where('vmonth', $request->month);
        }

        if ($request->filled('visitor_type') && $request->visitor_type != 'all') {
            $query->where('visitor_type', $request->visitor_type);
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

        $labels = $visits->map(fn($item) => Carbon::create($item->vyear, $item->vmonth, 1)->format('F Y'));
        $data = $visits->pluck('total');
        $residentData = $visits->pluck('resident_total');
        $visitorData = $visits->pluck('visitor_total');

        $years = VisitorCount::select('vyear')->distinct()->orderBy('vyear', 'DESC')->pluck('vyear');

        return view('admin.list.monthlyvisits', compact('labels', 'data', 'residentData', 'visitorData', 'years'));
    }

    public function overview(Request $request)
    {
        $search = $request->search;
        $month  = $request->month;
        $year   = $request->year;
        $vtype  = $request->visitor_type;

        $query = VisitorCount::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('vmonth', 'like', "%{$search}%")
                    ->orWhere('vyear', 'like', "%{$search}%")
                    ->orWhere('loc', 'like', "%{$search}%")
                    ->orWhere('visitor_type', 'like', "%{$search}%")
                    ->orWhere('total_visitors', 'like', "%{$search}%");
            });
        }

        if ($month && $month !== 'all') $query->where('vmonth', $month);
        if ($year && $year !== 'all') $query->where('vyear', $year);
        if ($vtype && $vtype !== 'all') $query->where('visitor_type', $vtype);

        $mvisits = $query->orderBy('vyear', 'desc')
            ->orderBy('vmonth', 'asc')
            ->paginate(10)
            ->appends($request->except('page'));

        $years = VisitorCount::select('vyear')->distinct()->orderBy('vyear', 'desc')->pluck('vyear');

        return view('admin.list.monthlyvisitsoverview', compact('mvisits', 'years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vdate' => 'required|date',
            'vcounts' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
            'visitor_type' => 'required|in:resident,visitor',
        ]);

        $date = Carbon::parse($request->vdate);

        VisitorCount::create([
            'vmonth' => $date->month,
            'vyear' => $date->year,
            'total_visitors' => $request->vcounts,
            'loc' => $request->location,
            'visitor_type' => $request->visitor_type,
            'date_add' => now(),
        ]);

        return redirect()->route('monthlyvisits.overview')->with('success', 'Monthly visit added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'total_visitors' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
            'visitor_type' => 'required|in:resident,visitor',
        ]);

        $visit = VisitorCount::findOrFail($id);
        $visit->update([
            'loc' => $request->location,
            'visitor_type' => $request->visitor_type,
            'total_visitors' => $request->total_visitors,
        ]);

        return redirect()->route('monthlyvisits.overview')->with('success', 'Monthly visit record updated successfully.');
    }

    public function destroy($id)
    {
        try {
            VisitorCount::findOrFail($id)->delete();
            session()->flash('success', 'Record deleted successfully.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.']);
        }
    }
}