<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Events;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $events = Events::query()
            ->where('status', '!=', 2)
            ->when($search, fn($query) => $query->where('events', 'like', "%$search%"))
            ->when($request->filled('status') && in_array($status, ['0', '1']), fn($query) => $query->where('status', $status))
            ->orderBy('e_datetime', 'desc')
            ->paginate(10)->appends($request->only(['search', 'status']));

        return view('admin.list.eventadmin', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'events'     => 'required|string|max:255',
            'e_info'     => 'nullable|string',
            'e_datetime' => 'required|date',
            'e_location' => 'required|string|max:255',
            'e_maplink'  => 'nullable|url',
            'e_link'     => 'nullable|string',
            'status'     => 'required|in:0,1',
            'pics'       => 'nullable|array',
            'pics.*'     => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:6048',
        ]);

        $images = [];

        if ($request->hasFile('pics')) {
            foreach ($request->file('pics') as $file) {
                if ($file->isValid()) {
                    $images[] = $file->store('events', 's3');
                }
            }
        }

        Events::create([
            'events'     => $request->events,
            'e_info'     => $request->e_info,
            'e_datetime' => $request->e_datetime,
            'e_location' => $request->e_location,
            'e_maplink'  => $request->e_maplink,
            'e_link'     => $request->e_link,
            'status'     => $request->status,
            'pics'       => $images,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'events'     => 'required|string|max:255',
            'e_info'     => 'nullable|string',
            'e_datetime' => 'required|date',
            'e_location' => 'required|string|max:255',
            'e_maplink'  => 'nullable|url',
            'e_link'     => 'nullable|string',
            'status'     => 'required|in:0,1',
            'pics'       => 'nullable|array',
            'pics.*'     => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:6048',
        ]);

        $event = Events::findOrFail($id);
        $images = $event->pics ?? [];

        if ($request->hasFile('pics')) {
            foreach ($request->file('pics') as $file) {
                if ($file->isValid()) {
                    $images[] = $file->store('events', 's3');
                }
            }
        }

        $event->update([
            'events'     => $request->events,
            'e_info'     => $request->e_info,
            'e_datetime' => $request->e_datetime,
            'e_location' => $request->e_location,
            'e_maplink'  => $request->e_maplink,
            'e_link'     => $request->e_link,
            'status'     => $request->status,
            'pics'       => $images,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function removeImage(Request $request, $id)
    {
        $event = Events::findOrFail($id);
        $imageToRemove = $request->image;
        $pics = $event->pics ?? [];

        $updated = array_filter($pics, function ($img) use ($imageToRemove) {
            return $img !== $imageToRemove;
        });

        if (!empty($imageToRemove) && Storage::disk('s3')->exists($imageToRemove)) {
            Storage::disk('s3')->delete($imageToRemove);
        }

        $event->pics = array_values($updated);
        $event->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        try {
            $event = Events::findOrFail($id);
            $event->update(['status' => 2]);

            session()->flash('success', 'Event moved to trash successfully.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to move event to trash.'
            ]);
        }
    }

    public function trash(Request $request)
    {
        $search = $request->search;

        $events = Events::query()
            ->where('status', 2)
            ->when($search, fn($query) => $query->where('events', 'like', "%$search%"))
            ->orderBy('e_datetime', 'desc')
            ->paginate(10)->appends($request->only(['search']));

        return view('admin.list.bin.eventstrash', compact('events'));
    }

    public function restore($id)
    {
        try {
            $event = Events::findOrFail($id);
            $event->update(['status' => 1]);

            session()->flash('success', 'Event restored successfully.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to restore event.']);
        }
    }

    public function forceDelete($id)
    {
        try {
            $event = Events::findOrFail($id);

            $images = $event->pics ?? [];
            foreach ($images as $img) {
                if (Storage::disk('s3')->exists($img)) {
                    Storage::disk('s3')->delete($img);
                }
            }

            $event->delete();

            session()->flash('success', 'Event permanently deleted.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete event.'
            ]);
        }
    }
}