<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exploreplaces;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $place = $request->place;
        $status = $request->status;

        $reviews = Review::with('place')

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })

            ->when($place, function ($query) use ($place) {
                $query->whereHas('place', function ($q) use ($place) {
                    $q->where('name', 'like', '%' . $place . '%');
                });
            })

            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })


            ->orderBy('date', 'desc')
            ->get();

        $places = Exploreplaces::all();

        return view('admin.list.reviewsadmin', compact('reviews', 'places'));
    }

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->status = 1;
        $review->save();

        return back()->with('success', 'Review approved');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Review deleted');
    }
}
