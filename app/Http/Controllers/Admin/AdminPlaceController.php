<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exploreplaces;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPlaceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $places = Exploreplaces::with('categories')
            ->where('status', '!=', 2)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->filled('status') && in_array($status, ['0', '1']), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->only(['search', 'status']));

        $categories = Category::where('status', 1)->get();

        return view('admin.list.placeadmin', compact('places', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'link1' => 'nullable|url',
            'link2' => 'nullable|url',
            'map_link' => 'nullable|url',
            'opening_hours' => 'nullable|string|max:255',
            'transport' => 'nullable|string',
            'description' => 'nullable|string',
            'history' => 'nullable|string',
            'status' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'categories' => 'nullable|array',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
        ]);

        $uploadedImages = [];

        if ($request->hasFile('main_image')) {
            $uploadedImages[] = $request->file('main_image')->store('places', 's3');
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $uploadedImages[] = $image->store('places', 's3');
            }
        }

        $place = Exploreplaces::create([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'email' => $request->email,
            'link1' => $request->link1,
            'link2' => $request->link2,
            'map_link' => $request->map_link,
            'opening_hours' => $request->opening_hours,
            'transport' => $request->transport,
            'description' => $request->description,
            'history' => $request->history,
            'status' => $request->has('status') ? 1 : 0,
            'is_popular' => $request->has('is_popular') ? 1 : 0,
            'images' => $uploadedImages,
        ]);

        $place->categories()->sync($request->categories ?? []);

        return redirect()->route('admin.places.index')->with('success', 'Place added successfully.');
    }

    public function show($id)
    {
        $place = Exploreplaces::with('categories')->findOrFail($id);
        return response()->json($place);
    }

    public function removeImage(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|string'
        ]);

        $place = Exploreplaces::findOrFail($id);
        $imageToRemove = $request->image;

        $images = $place->images;
        if (is_string($images)) {
            $images = json_decode($images, true);
        }
        if (!is_array($images)) {
            $images = [];
        }

        $updatedImages = array_filter($images, function ($img) use ($imageToRemove) {
            return $img !== $imageToRemove;
        });

        if (!empty($imageToRemove) && Storage::disk('s3')->exists($imageToRemove)) {
            Storage::disk('s3')->delete($imageToRemove);
        }

        $place->images = array_values($updatedImages);
        $place->save();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $place = Exploreplaces::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'link1' => 'nullable|url',
            'link2' => 'nullable|url',
            'map_link' => 'nullable|url',
            'opening_hours' => 'nullable|string|max:255',
            'transport' => 'nullable|string',
            'description' => 'nullable|string',
            'history' => 'nullable|string',
            'status' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'categories' => 'nullable|array',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
        ]);

        $existingImages = $place->images;
        if (is_string($existingImages)) {
            $existingImages = json_decode($existingImages, true);
        }
        if (!is_array($existingImages)) {
            $existingImages = [];
        }

        if ($request->hasFile('main_image')) {
            $mainImgPath = $request->file('main_image')->store('places', 's3');
            array_unshift($existingImages, $mainImgPath);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $existingImages[] = $image->store('places', 's3');
            }
        }

        $place->update([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'email' => $request->email,
            'link1' => $request->link1,
            'link2' => $request->link2,
            'map_link' => $request->map_link,
            'opening_hours' => $request->opening_hours,
            'transport' => $request->transport,
            'description' => $request->description,
            'history' => $request->history,
            'status' => $request->has('status') ? 1 : 0,
            'is_popular' => $request->has('is_popular') ? 1 : 0,
            'images' => $existingImages,
        ]);

        $place->categories()->sync($request->categories ?? []);

        return redirect()->route('admin.places.index')->with('success', 'Place updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $place = Exploreplaces::findOrFail($id);
            $place->update(['status' => 2]);

            session()->flash('success', 'Place moved to trash successfully.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to move place to trash.'
            ]);
        }
    }

    public function trash(Request $request)
    {
        $search = $request->search;

        $places = Exploreplaces::with('categories')
            ->where('status', 2)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->only(['search']));

        return view('admin.list.bin.placestrash', compact('places'));
    }

    public function restore($id)
    {
        try {
            $place = Exploreplaces::findOrFail($id);
            $place->update(['status' => 1]);

            session()->flash('success', 'Place restored successfully.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to restore place.']);
        }
    }

    public function forceDelete($id)
    {
        try {
            $place = Exploreplaces::findOrFail($id);

            $images = $place->images;
            if (is_string($images)) {
                $images = json_decode($images, true);
            }
            if (!is_array($images)) {
                $images = [];
            }

            foreach ($images as $img) {
                if (Storage::disk('s3')->exists($img)) {
                    Storage::disk('s3')->delete($img);
                }
            }

            $place->categories()->detach();
            $place->delete();

            session()->flash('success', 'Place permanently deleted.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete place.'
            ]);
        }
    }
}