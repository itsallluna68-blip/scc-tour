<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Exploreplaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

    // ================= STORE =================
    public function store(Request $request)
    {
        // dd($request->all(), $request->file());


        $request->validate([
            'name' => 'required|string|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp
            max:10240',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
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

        // $imagesData = [];

        // if ($request->hasFile('main_image')) {
        //     $mainFile = $request->file('main_image');
        //     $imagesData[] = base64_encode(file_get_contents($mainFile)); // encode binary data
        // }

        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $file) {
        //         $imagesData[] = base64_encode(file_get_contents($file));
        //     }
        // }


        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     $place->images = file_get_contents($image->getRealPath());
        // }


        // if ($request->hasFile('images')) {
        //     $imageContents = [];

        //     if ($request->hasFile('main_image')) {
        //         $mainFile = $request->file('main_image');
        //         $imageContents[] = file_get_contents($mainFile->getRealPath());
        //     }

        //     foreach ($request->file('images') as $image) {
        //         // Read the file content as binary
        //         $imageContents[] = file_get_contents($image->getRealPath());
        //     }

        //     // Serialize the array of images into one string
        //     $place->images = serialize($imageContents);
        // }

        //  IMAGE STORAGE USING INTERVENTION

        $imagesData = [
            'main' => null,
            'gallery' => []
        ];

        // MAIN IMAGE
    if ($request->hasFile('main_image')) {
        $file = $request->file('main_image');

        // Intervention/Image resize & compress
        $img = Image::make($file->getRealPath())
            ->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('jpg', 75);

        $filename = time() . '_main_' . uniqid() . '.jpg';
        $path = 'places/' . $filename;

        // Save to storage/app/public/places
        Storage::disk('public')->put($path, $img);

        // Also save the original file using Laravel's store() (optional)
        $imagesData['main'] = $path;
    }


        // GALLERY IMAGES
     if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            // Intervention/Image resize & compress
            $img = Image::make($file->getRealPath())
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 75);

            $filename = time() . '_gallery_' . uniqid() . '.jpg';
            $path = 'places/' . $filename;

            // Save to storage
            Storage::disk('public')->put($path, $img);

            // Save path to JSON gallery array
            $imagesData['gallery'][] = $path;
        }
        }
        $place->images = json_encode($imagesData);



        // Save encoded images in the images field (as JSON)
        //$place->images = json_encode($imagesData);
        // Save place
        $place->save();

        // Attach categories
        if ($request->has('categories')) {
            $place->categories()->sync($request->categories);
        }

        return redirect()->route('admin.places.index')
            ->with('success', 'Place added successfully.');
    }

    // ================= UPDATE =================
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

        // ✅ Get existing images
        // $imagePaths = $place->images ?? [];

        // Replace main image
        // if ($request->hasFile('main_image')) {
        //     $mainPath = $request->file('main_image')->store('places', 'public');

        //     if (count($imagePaths) > 0) {
        //         $imagePaths[0] = $mainPath;
        //     } else {
        //         $imagePaths[] = $mainPath;
        //     }
        // }

        // // Append gallery images
        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $file) {
        //         $imagePaths[] = $file->store('places', 'public');
        //     }
        // }

        // IMAGE STORAGE USING INTERVENTION
        $imagesData = json_decode($place->images, true) ?? [
            'main' => null,
            'gallery' => []
        ];

        // REPLACE MAIN IMAGE
    if ($request->hasFile('main_image')) {
        $file = $request->file('main_image');

        // Intervention/Image resize & compress
        $img = Image::make($file->getRealPath())
            ->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('jpg', 75);

        $filename = time() . '_main_' . uniqid() . '.jpg';
        $path = 'places/' . $filename;

        // Save to storage/app/public/places
        Storage::disk('public')->put($path, $img);

        // Also save the original file using Laravel's store() (optional)
        $imagesData['main'] = $path;
    }


        // ADD GALLERY IMAGES
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            // Intervention/Image resize & compress
            $img = Image::make($file->getRealPath())
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 75);

            $filename = time() . '_gallery_' . uniqid() . '.jpg';
            $path = 'places/' . $filename;

            // Save to storage
            Storage::disk('public')->put($path, $img);

            // Save path to JSON gallery array
            $imagesData['gallery'][] = $path;
        }
        }

        $place->images = json_encode($imagesData);

        $place->save();

        // Sync categories
        if ($request->has('categories')) {
            $place->categories()->sync($request->categories);
        }

        return redirect()->route('admin.places.index')
            ->with('success', 'Place updated successfully.');
    }

    // remove image
    public function removeImage(Request $request, $id)
    {
        $place = Exploreplaces::findOrFail($id);

        $images = json_decode($place->images, true);

        $imageToDelete = $request->image;

        // remove from storage
        Storage::disk('public')->delete($imageToDelete);

        // remove from array
        if ($images['main'] === $imageToDelete) {
            $images['main'] = null;
        }

        if (!empty($images['gallery'])) {
            $images['gallery'] = array_values(
                array_filter($images['gallery'], fn($img) => $img !== $imageToDelete)
            );
        }

        $place->images = json_encode($images);
        $place->save();

        return response()->json(['success' => true]);
    }
}