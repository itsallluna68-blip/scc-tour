<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TouristPlace;
use App\Models\Category;
use App\Models\Review;

class TouristPlaceController extends Controller
{
    public function index()
    {
        $tplaces = TouristPlace::where('status', 1)->get();
        $categories = Category::all();

        return view('admin.list.touristplace', compact('tplaces', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'description' => 'required|string',
            'history' => 'nullable|string',
            'link1' => 'nullable|url',
            'link2' => 'nullable|url',
            'address' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'category1' => 'nullable|string|max:255',
            'category2' => 'nullable|string|max:255',
            'category3' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
            'image0' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'image1' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'image2' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'image3' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $validated['status'] = $validated['status'] ?? 1;

        for ($i = 0; $i < 4; $i++) {
            $field = 'image' . $i;
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . "_{$field}." . $file->getClientOriginalExtension();
                $file->move(public_path('image'), $filename);
                $validated[$field] = $filename;
            }
        }

        TouristPlace::create($validated);

        return redirect()->back()->with('success', 'Tourist Place added successfully!');
    }

    // EDIT TOURIST PLACE
        public function update(Request $request, $id)
    {
        $place = TouristPlace::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'history' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'category1' => 'nullable|string|max:255',
            'category2' => 'nullable|string|max:255',
            'category3' => 'nullable|string|max:255',
            'image0' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'image1' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'image2' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'image3' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Prevent status changes
        $validated['status'] = $place->status;

        // Handle new images if uploaded
        for ($i = 0; $i < 4; $i++) {
            $field = 'image' . $i;
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . "_{$field}." . $file->getClientOriginalExtension();
                $file->move(public_path('image'), $filename);
                $validated[$field] = $filename;
            }
        }

        $place->update($validated);

        return redirect()->back()->with('success', 'Tourist Place updated successfully!');
    }

    // REVIEWS
    

}
