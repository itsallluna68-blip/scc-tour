<?php

namespace App\Http\Controllers;

use App\Models\Activity;
// use App\Models\Category;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search');

    $activities = Activity::where('a_status', 1)
        ->when($search, function ($query) use ($search) {
            $query->where('a_name', 'like', '%' . $search . '%');
        })
        ->with('categories.places')
        ->get();

    return view('public.activitylist', compact('activities', 'search'));
}


    public function show($id)
    {
        $activity = Activity::where('a_status', 1)
        ->findOrFail($id);
        return view('public.activitydetails', compact('activity'));
    }
}
