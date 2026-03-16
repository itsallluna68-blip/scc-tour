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
            ->with('categories')
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

    public function store(Request $request)
    {
        $request->validate([
            'a_name' => 'required|string|max:255',
            'a_info' => 'nullable|string',
            'img0'   => 'required|image|max:5048',
            'categories' => 'nullable|array',
        ]);

        $imagePath = null;
        if ($request->hasFile('img0')) {
            $image = $request->file('img0');
            $imageName = time() . '_' . Str::slug($request->a_name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/activities'), $imageName);
            $imagePath = 'uploads/activities/' . $imageName;
        }

        $activity = Activity::create([
            'a_name'   => $request->a_name,
            'a_info'   => $request->a_info,
            'img0'     => $imagePath,
            'a_status' => 1,
        ]);

        if ($request->filled('categories')) {
            $activity->categories()->attach($request->categories);
        }

        return redirect()->route('admin.activities.index')->with('success', 'Activity added successfully.');
    }

    public function update(Request $request, $aid)
    {
        $activity = Activity::where('aid', $aid)->firstOrFail();

        $request->validate([
            'a_name' => 'required|string|max:255',
            'a_info' => 'nullable|string',
            'img0'   => 'nullable|image|max:5048',
            'a_status' => 'required|boolean',
            'categories' => 'nullable|array',
        ]);

        if ($request->hasFile('img0')) {
            $image = $request->file('img0');
            $imageName = time() . '_' . Str::slug($request->a_name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/activities'), $imageName);
            $activity->img0 = 'uploads/activities/' . $imageName;
        }

        $activity->a_name   = $request->a_name;
        $activity->a_info   = $request->a_info;
        $activity->a_status = $request->a_status;
        $activity->save();

        $activity->categories()->sync($request->categories ?? []);

        return redirect()->route('admin.activities.index')->with('success', 'Activity updated successfully.');
    }

    public function destroy($aid)
    {
        $activity = Activity::where('aid', $aid)->firstOrFail();

        if ($activity->img0 && file_exists(public_path($activity->img0))) {
            unlink(public_path($activity->img0));
        }

        $activity->categories()->detach();
        $activity->delete();

        return redirect()->route('admin.activities.index')->with('success', 'Activity deleted successfully.');
    }
}