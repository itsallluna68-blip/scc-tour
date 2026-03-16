<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function edit()
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized access. Only Admin can manage settings.');
        }

        $settings = Setting::all()->pluck('details', 'term')->toArray();

        if (isset($settings['historyImg'])) {
            $settings['historyImg'] = json_decode($settings['historyImg'], true);
        }

        if (isset($settings['bgImg'])) {
            $settings['bgImg'] = json_decode($settings['bgImg'], true);
        }

        return view('admin.list.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized access. Only Admin can manage settings.');
        }

        $textFields = ['tagline', 'historyTxt', 'aboutUs', 'address', 'telephone', 'mobile', 'email'];

        foreach ($textFields as $field) {
            if ($request->has($field)) {
                $this->updateSetting($field, $request->$field);
            }
        }

        if ($request->hasFile('bgImg')) {
            $existing = Setting::where('term', 'bgImg')->value('details');
            $existingImages = json_decode($existing, true);
            $existingImages = is_array($existingImages) ? $existingImages : [];
            $newImages = [];

            foreach ($request->file('bgImg') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $newImages[] = $filename;
            }

            $allImages = array_merge($existingImages, $newImages);

            Setting::updateOrInsert(
                ['term' => 'bgImg'],
                ['details' => json_encode($allImages)]
            );
        }

        if ($request->hasFile('historyImg')) {
            $existing = Setting::where('term', 'historyImg')->value('details');
            $existingImages = json_decode($existing, true);
            $existingImages = is_array($existingImages) ? $existingImages : [];
            $newImages = [];

            foreach ($request->file('historyImg') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $newImages[] = $filename;
            }

            $allImages = array_merge($existingImages, $newImages);

            Setting::updateOrInsert(
                ['term' => 'historyImg'],
                ['details' => json_encode($allImages)]
            );
        }

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated successfully.');
    }

    private function updateSetting($term, $value)
    {
        if (!is_null($value)) {
            Setting::updateOrInsert(
                ['term' => $term],
                ['details' => $value]
            );
        }
    }

    public function removeImage(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized access. Only Admin can manage settings.');
        }

        $imageToDelete = $request->image;
        $existing = Setting::where('term', 'historyImg')->value('details');
        $images = $existing ? json_decode($existing, true) : [];

        $updatedImages = array_filter($images, function ($img) use ($imageToDelete) {
            return $img !== $imageToDelete;
        });

        $filePath = public_path('uploads/settings/' . $imageToDelete);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        Setting::updateOrInsert(
            ['term' => 'historyImg'],
            ['details' => json_encode(array_values($updatedImages))]
        );

        return back();
    }

    public function ajaxDeleteImage(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $image = $request->image;
        $type = $request->type;

        if (!$image || !$type) {
            return response()->json(['success' => false]);
        }

        $term = $type === 'background' ? 'bgImg' : 'historyImg';
        $setting = Setting::where('term', $term)->first();

        if (!$setting) {
            return response()->json(['success' => false]);
        }

        $images = json_decode($setting->details, true) ?? [];
        $images = array_filter($images, fn($img) => $img !== $image);

        $path = public_path('uploads/settings/' . $image);
        if (file_exists($path)) {
            unlink($path);
        }

        $setting->update([
            'details' => json_encode(array_values($images))
        ]);

        return response()->json(['success' => true]);
    }
}