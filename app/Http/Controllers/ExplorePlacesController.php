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

        // Only get categories with status = 1
        $categories = PlaceCategory::where('status', 1)->get();

        // Only get activities with a_status = 1 and linked to selected categories
        $activities = Activity::where('a_status', 1)
            ->when(!empty($selectedCategories), function ($query) use ($selectedCategories) {
                $query->whereHas('categories', function ($q) use ($selectedCategories) {
                    $q->whereIn('cid', $selectedCategories);
                });
            })
            ->get();



        // Filter places
        $exploreplaces = Exploreplaces::with('categories')

            // Reviews
            ->withAvg('reviews', 'ratings')
            ->withCount('reviews')

            ->where('status', 1)
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when(!empty($selectedCategories), function ($query) use ($selectedCategories) {
                // Only show places that have ALL selected categories
                foreach ($selectedCategories as $catId) {
                    $query->whereHas('categories', function ($q) use ($catId) {
                        $q->where('cid', $catId);
                    });
                }
            })
            ->when(!empty($selectedActivities), function ($query) use ($selectedActivities) {
                $query->whereHas('activities', function ($q) use ($selectedActivities) {
                    $q->where('aid', $selectedActivities[0]); // Only allow one activity at a time
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
        $place = Exploreplaces::with([
            'categories',
            'reviews' => function ($query) {
                $query->orderBy('date', 'desc');
            }
        ])->findOrFail($id);

        $categoryIds = $place->categories->pluck('cid');

        // Get similar places
        $similarPlaces = Exploreplaces::with('categories')
            ->where('status', 1)
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('tblcategories.cid', $categoryIds);
            })
            ->where('id', '!=', $place->id)
            ->limit(3)
            ->get();

        // ✅ Calculate average rating
        $averageRating = $place->reviews->count()
            ? round($place->reviews->avg('ratings'), 1)
            : 0;


        // ✅ Total reviews
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
        'email' => 'required|email|max:255',
        'ratings' => 'required|integer|min:1|max:5',
        'feedback' => 'required|string',
        'g-recaptcha-response' => 'required',
        'images.*' => 'nullable|image|max:2048',
    ]);

    $ip = $request->ip();
    $email = $request->email;

    $existingEmailReview = Review::where('place_id', $placeId)
        ->where('email', $email)
        ->first();
    // email review limit
    if ($existingEmailReview) {
        return back()->withErrors([
            'duplicate_review' => 'You have already reviewed this place using this email.'
        ])->withInput();
    }
    // review limit
    $recentIpReview = Review::where('place_id', $placeId)
        ->where('ip_address', $ip)
        ->where('date', '>=', now()->subHour())
        ->first();

    if ($recentIpReview) {
        return back()->withErrors([
            'duplicate_review' => 'A review from your network was recently submitted. Please wait before submitting another review.'
        ])->withInput();
    }

    // Verify reCAPTCHA
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
            'g-recaptcha-response' => 'reCAPTCHA verification failed.'
        ])->withInput();
    }

    // Save review
    $review = new Review();
    $review->place_id = $placeId;
    $review->name = $request->name;
    $review->email = $email;
    $review->ratings = $request->ratings;
    $review->feedback = $request->feedback;
    $review->date = now();
    $review->ip_address = $ip;

    if ($request->file('images')) {
        foreach ($request->file('images') as $index => $file) {
            if ($index > 2) break;
            $review->{'rpic' . $index} = $file->store('reviews', 'public');
        }
    }

    $review->save();

    return redirect()->route('exploreplaces.show', $placeId)
    ->with('success', 'Review submitted successfully!');
}
}
