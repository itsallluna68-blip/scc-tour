<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $activities = Activity::query()
            ->with('categories')
            ->where('a_status', '!=', 2)
            ->when($search, function ($query) use ($search) {
                $query->where('a_name', 'like', "%{$search}%");
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('a_status', $status);
            })
            ->paginate(10)->appends($request->only(['search', 'status']));

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
            $imagePath = $request->file('img0')->store('activities', 's3');
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

        if ($request->input('remove_image') == '1') {
            if ($activity->img0 && Storage::disk('s3')->exists($activity->img0)) {
                Storage::disk('s3')->delete($activity->img0);
            }
            $activity->img0 = '';
        }

        if ($request->hasFile('img0')) {
            if ($activity->img0 && Storage::disk('s3')->exists($activity->img0)) {
                Storage::disk('s3')->delete($activity->img0);
            }
            $activity->img0 = $request->file('img0')->store('activities', 's3');
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
        try {
            $activity = Activity::where('aid', $aid)->firstOrFail();

            $activity->update(['a_status' => 2]);

            session()->flash('success', 'Activity moved to trash successfully.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to move activity to trash.'
            ]);
        }
    }

    public function trash(Request $request)
    {
        $search = $request->search;

        $activities = Activity::query()
            ->with('categories')
            ->where('a_status', 2)
            ->when($search, function ($query) use ($search) {
                $query->where('a_name', 'like', "%{$search}%");
            })
            ->paginate(10)->appends($request->only(['search']));

        return view('admin.list.bin.activitytrash', compact('activities'));
    }

    public function restore($aid)
    {
        try {
            $activity = Activity::where('aid', $aid)->firstOrFail();
            $activity->update(['a_status' => 1]);

            session()->flash('success', 'Activity restored successfully.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to restore activity.']);
        }
    }

    public function forceDelete($aid)
    {
        try {
            $activity = Activity::where('aid', $aid)->firstOrFail();

            if ($activity->img0 && Storage::disk('s3')->exists($activity->img0)) {
                Storage::disk('s3')->delete($activity->img0);
            }

            $activity->categories()->detach();
            $activity->delete();

            session()->flash('success', 'Activity permanently deleted.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete activity.'
            ]);
        }
    }
}