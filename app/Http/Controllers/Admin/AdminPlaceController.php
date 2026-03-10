<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Exploreplaces;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary; // The Cloudinary Facade

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
        $request->validate([
            'name' => 'required|string|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        try {
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

            $imagesData = ['main' => null, 'gallery' => []];

            // MAIN IMAGE UPLOAD
            if ($request->hasFile('main_image')) {
                $img = Image::make($request->file('main_image')->getRealPath())
                    ->orientate()
                    ->resize(1200, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->encode('jpg', 75);

                // Uploading the processed stream to Cloudinary
                $uploadedFile = Cloudinary::upload((string) $img->encode('data-url'), [
                    'folder' => 'places'
                ]);
                $imagesData['main'] = $uploadedFile->getSecurePath();
            }

            // GALLERY IMAGES UPLOAD
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $img = Image::make($file->getRealPath())
                        ->orientate()
                        ->resize(1000, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('jpg', 70);

                    $uploadedFile = Cloudinary::upload((string) $img->encode('data-url'), [
                        'folder' => 'places/gallery'
                    ]);
                    $imagesData['gallery'][] = $uploadedFile->getSecurePath();
                }
            }

            $place->images = json_encode($imagesData);
            $place->save();

            if ($request->has('categories')) {
                $place->categories()->sync($request->categories);
            }

            return redirect()->route('admin.places.index')->with('success', 'Place added and synced to Cloud!');

        } catch (\Exception $e) {
            // This will show you exactly why an upload failed (e.g., wrong API keys)
            return back()->withInput()->with('error', 'Cloudinary Error: ' . $e->getMessage());
        }
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $place = Exploreplaces::findOrFail($id);
        $imagesData = json_decode($place->images, true) ?? ['main' => null, 'gallery' => []];

        try {
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

            if ($request->hasFile('main_image')) {
                $img = Image::make($request->file('main_image')->getRealPath())
                    ->orientate()
                    ->resize(1200, null, function($c){ $c->aspectRatio(); })
                    ->encode('jpg', 75);
                
                $uploaded = Cloudinary::upload((string) $img->encode('data-url'), ['folder' => 'places']);
                $imagesData['main'] = $uploaded->getSecurePath();
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $img = Image::make($file->getRealPath())
                        ->orientate()
                        ->resize(1000, null, function($c){ $c->aspectRatio(); })
                        ->encode('jpg', 70);
                    
                    $uploaded = Cloudinary::upload((string) $img->encode('data-url'), ['folder' => 'places/gallery']);
                    $imagesData['gallery'][] = $uploaded->getSecurePath();
                }
            }

            $place->images = json_encode($imagesData);
            $place->save();

            if ($request->has('categories')) {
                $place->categories()->sync($request->categories);
            }

            return redirect()->route('admin.places.index')->with('success', 'Place updated in Cloud!');
        } catch (\Exception $e) {
            return back()->with('error', 'Update Error: ' . $e->getMessage());
        }
    }

    // ================= REMOVE IMAGE =================
    public function removeImage(Request $request, $id)
    {
        $place = Exploreplaces::findOrFail($id);
        $images = json_decode($place->images, true);
        $url = $request->image; // Full Cloudinary URL

        try {
            // Extract the Folder/PublicID from the URL
            // e.g. "https://res.cloudinary.com/demo/image/upload/v1/places/photo.jpg"
            // segments will give us "places/photo"
            $path = parse_url($url, PHP_URL_PATH);
            $segments = explode('/', $path);
            $filename = pathinfo(end($segments), PATHINFO_FILENAME);
            $folder = $segments[count($segments) - 2];
            
            $publicId = $folder . '/' . $filename;

            // Delete from Cloudinary servers
            Cloudinary::destroy($publicId);

            // Update local DB JSON
            if ($images['main'] === $url) {
                $images['main'] = null;
            }

            if (!empty($images['gallery'])) {
                $images['gallery'] = array_values(array_filter($images['gallery'], fn($img) => $img !== $url));
            }

            $place->images = json_encode($images);
            $place->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}