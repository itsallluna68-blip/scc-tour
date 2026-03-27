<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Exploreplaces;
use App\Models\PlaceCategory;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExplorePlacesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedCategories = $request->categories ?? [];
        $selectedActivities = $request->activities ?? [];
        $categories = PlaceCategory::where('status', 1)->get();

        $activities = Activity::where('a_status', 1)
            ->when(!empty($selectedCategories), function ($query) use ($selectedCategories) {
                $query->whereHas('categories', function ($q) use ($selectedCategories) {
                    $q->whereIn('cid', $selectedCategories);
                });
            })
            ->get();

        $exploreplaces = Exploreplaces::with('categories')
            ->withAvg(['reviews' => function ($query) {
                $query->where('status', 'approved');
            }], 'ratings')
            ->withCount(['reviews' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->where('status', 1)
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when(!empty($selectedCategories), function ($query) use ($selectedCategories) {
                foreach ($selectedCategories as $catId) {
                    $query->whereHas('categories', function ($q) use ($catId) {
                        $q->where('cid', $catId);
                    });
                }
            })
            ->when(!empty($selectedActivities), function ($query) use ($selectedActivities) {
                $query->whereHas('activities', function ($q) use ($selectedActivities) {
                    $q->where('aid', $selectedActivities[0]);
                });
            })
            ->orderByDesc('reviews_avg_ratings')
            ->get();

        return view('public.exploreplaces', compact(
            'exploreplaces',
            'categories',
            'activities',
            'selectedCategories',
            'selectedActivities',
            'search'
        ));
    }

    public function show($id)
    {
        $place = Exploreplaces::where('status', 1)->with([
            'categories',
            'reviews' => function ($query) {
                $query->where('status', 'approved')->orderBy('date', 'desc');
            }
        ])->findOrFail($id);

        $categoryIds = $place->categories->pluck('cid');

        $similarPlaces = Exploreplaces::with('categories')
            ->where('status', 1)
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('tblcategories.cid', $categoryIds);
            })
            ->where('id', '!=', $place->id)
            ->limit(3)
            ->get();

        $averageRating = $place->reviews->count()
            ? round($place->reviews->avg('ratings'), 1)
            : 0;
        $reviewCount = $place->reviews->count();
        $reviews = $place->reviews;

        return view('public.exploreplaces-show', compact(
            'place',
            'averageRating',
            'reviewCount',
            'reviews',
            'similarPlaces'
        ));
    }

    public function storeReview(Request $request, $placeId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ratings' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string',
            'g-recaptcha-response' => 'required',
            'images.*' => 'nullable|image|max:5048',
        ]);

        $ip = $request->ip();

        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $ip,
            ]
        );

        $recaptchaData = $response->json();

        if (!($recaptchaData['success'] ?? false)) {
            return back()->withErrors([
                'g-recaptcha-response' => 'reCAPTCHA verification failed. Please check the box again.'
            ])->withInput();
        }

        try {
            $review = new Review();
            $review->place_id = $placeId;
            $review->name = $request->name;
            $review->ratings = $request->ratings;
            $review->feedback = $request->feedback;
            $review->date = now();
            $review->ip_address = $ip;
            $review->status = 'pending';

            if ($request->file('images')) {
                foreach ($request->file('images') as $index => $file) {
                    if ($index > 2) break;
                    $review->{'rpic' . $index} = $file->store('reviews', 's3');
                }
            }

            $review->save();

            return redirect()->route('exploreplaces.show', $placeId)
                ->with('success', 'Review submitted! It will be visible once approved.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'db_error' => 'Database Error: Not save. ' . $e->getMessage()
            ])->withInput();
        }
    }
}