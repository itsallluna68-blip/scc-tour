<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');

        $reviews = Review::with('place')
            ->when($status, function ($query) use ($status) {
                return $query->where('status', strtolower($status));
            })
            ->orderBy('date', 'desc')
            ->paginate(15)->appends($request->only(['status']));

        return view('admin.list.reviewadmin', compact('reviews', 'status'));
    }

    public function approve($rid)
    {
        try {
            $review = Review::findOrFail($rid);
            $review->update(['status' => 'approved']);

            session()->flash('success', 'Review approved/activated successfully.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to approve review.']);
        }
    }

    public function deactivate($rid)
    {
        try {
            $review = Review::findOrFail($rid);
            $review->update(['status' => 'deactivated']);

            session()->flash('success', 'Review deactivated and hidden from public.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to deactivate review.']);
        }
    }

    public function destroy($rid)
    {
        try {
            $review = Review::findOrFail($rid);
            for ($i = 0; $i <= 2; $i++) {
                $column = 'rpic' . $i;
                if ($review->$column && Storage::disk('s3')->exists($review->$column)) {
                    Storage::disk('s3')->delete($review->$column);
                }
            }

            $review->delete();

            session()->flash('success', 'Review deleted successfully.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete review.']);
        }
    }
}