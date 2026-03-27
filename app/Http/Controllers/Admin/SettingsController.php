<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit()
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403);
        }

        $settings = Setting::all()->pluck('details', 'term')->toArray();

        if (isset($settings['historyImg'])) {
            $settings['historyImg'] = json_decode($settings['historyImg'], true);
        }

        if (isset($settings['bgImg'])) {
            $settings['bgImg'] = json_decode($settings['bgImg'], true);
        }

        if (isset($settings['aboutUsImg'])) {
            $settings['aboutUsImg'] = json_decode($settings['aboutUsImg'], true);
        }

        return view('admin.list.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403);
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
            if (!is_array($existingImages)) {
                $existingImages = [];
            }

            $newImages = [];
            foreach ($request->file('bgImg') as $image) {
                $path = $image->store('settings/bg', 's3');
                $newImages[] = $path;
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
            if (!is_array($existingImages)) {
                $existingImages = [];
            }

            $newImages = [];
            foreach ($request->file('historyImg') as $image) {
                $path = $image->store('settings/history', 's3');
                $newImages[] = $path;
            }

            $allImages = array_merge($existingImages, $newImages);

            Setting::updateOrInsert(
                ['term' => 'historyImg'],
                ['details' => json_encode($allImages)]
            );
        }

        if ($request->hasFile('aboutUsImg')) {
            $existing = Setting::where('term', 'aboutUsImg')->value('details');
            $existingImages = json_decode($existing, true);
            if (!is_array($existingImages)) {
                $existingImages = [];
            }

            $newImages = [];
            foreach ($request->file('aboutUsImg') as $image) {
                $path = $image->store('settings/aboutus', 's3');
                $newImages[] = $path;
            }

            $allImages = array_merge($existingImages, $newImages);

            Setting::updateOrInsert(
                ['term' => 'aboutUsImg'],
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

    public function ajaxDeleteImage(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            return response()->json(['success' => false]);
        }

        $image = $request->image;
        $type = $request->type;

        if (!$image || !$type) {
            return response()->json(['success' => false]);
        }

        $term = match ($type) {
            'background' => 'bgImg',
            'history' => 'historyImg',
            'aboutus' => 'aboutUsImg',
            default => null
        };

        if (!$term) {
            return response()->json(['success' => false]);
        }

        $setting = Setting::where('term', $term)->first();

        if (!$setting) {
            return response()->json(['success' => false]);
        }

        $images = json_decode($setting->details, true) ?? [];
        $images = array_filter($images, fn($img) => $img !== $image);

        if (Storage::disk('s3')->exists($image)) {
            Storage::disk('s3')->delete($image);
        } elseif (Storage::exists($image)) {
            Storage::delete($image);
        }

        Setting::where('term', $term)->update([
            'details' => json_encode(array_values($images))
        ]);

        return response()->json(['success' => true]);
    }
}