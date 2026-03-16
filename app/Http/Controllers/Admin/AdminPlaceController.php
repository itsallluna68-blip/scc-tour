<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Exploreplaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPlaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Exploreplaces::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if (!is_null($request->input('status'))) {
            $query->where('status', $request->input('status'));
        }

        $places = $query->with('categories')->orderBy('id')->get();
        $categories = Category::where('status', 1)->get();

        return view('admin.list.placeadmin', compact('places', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $place = new Exploreplaces();
        $place->name = $request->name;
        $place->contact = $request->contact;
        $place->email = $request->email;
        $place->address = $request->address;
        $place->description = $request->description;
        $place->history = $request->history;
        $place->transport = $request->transport;
        $place->map_link = $request->map_link;
        $place->opening_hours = $request->opening_hours;
        $place->link1 = $request->link1;
        $place->link2 = $request->link2;
        $place->status = $request->has('status') ? 1 : 0;
        $place->is_popular = $request->has('is_popular') ? 1 : 0;

        $imagePaths = [];

        if ($request->hasFile('main_image')) {
            $mainPath = $request->file('main_image')->store('places', 'public');
            $imagePaths[] = $mainPath;
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('places', 'public');
            }
        }

        $place->images = $imagePaths;
        $place->save();

        if ($request->has('categories')) {
            $place->categories()->sync($request->categories);
        }

        return redirect()->route('admin.places.index')->with('success', 'Place added successfully.');
    }

    public function update(Request $request, $id)
    {
        $place = Exploreplaces::findOrFail($id);

        $place->name = $request->name;
        $place->contact = $request->contact;
        $place->email = $request->email;
        $place->address = $request->address;
        $place->description = $request->description;
        $place->history = $request->history;
        $place->transport = $request->transport;
        $place->map_link = $request->map_link;
        $place->opening_hours = $request->opening_hours;
        $place->link1 = $request->link1;
        $place->link2 = $request->link2;
        $place->status = $request->has('status') ? 1 : 0;
        $place->is_popular = $request->has('is_popular') ? 1 : 0;

        $imagePaths = $place->images ?? [];

        if ($request->hasFile('main_image')) {
            $mainPath = $request->file('main_image')->store('places', 'public');

            if (count($imagePaths) > 0) {
                $imagePaths[0] = $mainPath;
            } else {
                $imagePaths[] = $mainPath;
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('places', 'public');
            }
        }

        $place->images = $imagePaths;
        $place->save();

        if ($request->has('categories')) {
            $place->categories()->sync($request->categories);
        }

        return redirect()->route('admin.places.index')->with('success', 'Place updated successfully.');
    }

    public function removeImage(Request $request, $id)
    {
        $place = Exploreplaces::findOrFail($id);
        $imageToRemove = $request->image;
        $images = $place->images ?? [];

        $updatedImages = array_filter($images, function ($img) use ($imageToRemove) {
            return $img !== $imageToRemove;
        });

        if (Storage::disk('public')->exists($imageToRemove)) {
            Storage::disk('public')->delete($imageToRemove);
        }

        $place->images = array_values($updatedImages);
        $place->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $place = Exploreplaces::findOrFail($id);

        $images = $place->images ?? [];
        foreach ($images as $img) {
            if (Storage::disk('public')->exists($img)) {
                Storage::disk('public')->delete($img);
            }
        }

        $place->categories()->detach();
        $place->delete();

        return redirect()->route('admin.places.index')->with('success', 'Place deleted successfully.');
    }
}