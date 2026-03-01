<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;
    $status = $request->status;

    $activities = Activity::query()

        ->when($search, function ($query) use ($search) {
            $query->where('a_name', 'like', "%{$search}%");
        })

        ->when($status !== null && $status !== '', function ($query) use ($status) {
            $query->where('a_status', $status);
        })

        ->get();

    $categories = Category::where('status', 1)->get();

    return view('admin.list.activityadmin', compact('activities', 'categories'));
}

    // ADMIN CONTROLLER NI! WAG TANGAIINSSSS GRRRR
public function store(Request $request)
{
    // Validate input
    $request->validate([
        'a_name' => 'required|string|max:255',
        'a_info' => 'nullable|string',          // optional info
        'img0'   => 'required|image|max:5048',  // only 1 image allowed
        'categories' => 'nullable|array',
    ]);

    // Handle uploaded image
    $imagePath = null;
    if ($request->hasFile('img0')) {
        $image = $request->file('img0');
        $imageName = time() . '_' . Str::slug($request->a_name) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/activities'), $imageName);
        $imagePath = 'uploads/activities/' . $imageName;
    }

    // Create activity
    $activity = Activity::create([
        'a_name'   => $request->a_name,
        'a_info'   => $request->a_info,   // will be NULL if empty
        'img0'     => $imagePath,
        'a_status' => 1,
    ]);

    // Attach categories if any
    if ($request->filled('categories')) {
        $activity->categories()->attach($request->categories);
    }

    return redirect()->route('admin.activities.index')
                     ->with('success', 'Activity added successfully.');
}

    // ADMIN CONTROLLER LAGI NI! END ENESMOATEKOURGGH GRRRR
    
public function update(Request $request, $aid)
{
    $activity = Activity::where('aid', $aid)->firstOrFail();

    // Validate input
    $request->validate([
        'a_name' => 'required|string|max:255',
        'a_info' => 'nullable|string',
        'img0'   => 'nullable|image|max:5048', // optional new image
        'a_status' => 'required|boolean',
        'categories' => 'nullable|array',
    ]);

    // Handle uploaded image (if a new file is selected)
    if ($request->hasFile('img0')) {
        $image = $request->file('img0');
        $imageName = time() . '_' . Str::slug($request->a_name) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/activities'), $imageName);
        $activity->img0 = 'uploads/activities/' . $imageName;
    }

    // Update other fields
    $activity->a_name   = $request->a_name;
    $activity->a_info   = $request->a_info;  // nullable
    $activity->a_status = $request->a_status;
    $activity->save();

    // Sync categories
    $activity->categories()->sync($request->categories ?? []);

    return redirect()->route('admin.activities.index')
                     ->with('success', 'Activity updated successfully.');
}
}