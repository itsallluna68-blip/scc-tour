<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()->where('status', '!=', 2);

        if ($request->filled('search')) {
            $query->where('category', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status') && in_array($request->status, ['0', '1'])) {
            $query->where('status', $request->status);
        }

        $categories = $query->orderBy('cid')->paginate(10)->appends($request->only(['search', 'status']));

        return view('admin.list.categoryadmin', compact('categories'));
    }

    public function create()
    {
        return view('admin.list.categorycreate');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'category' => $request->category,
            'description' => $request->description,
            'status' => 1
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category added successfully.');
    }

    public function update(Request $request, $cid)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1'
        ]);

        $category = Category::findOrFail($cid);

        $category->update([
            'category' => $request->category,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($cid)
    {
        try {
            $category = Category::findOrFail($cid);
            $category->update(['status' => 2]);

            session()->flash('success', 'Category moved to trash successfully.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to move category to trash.'
            ]);
        }
    }

    public function trash(Request $request)
    {
        $query = Category::query()->where('status', 2);

        if ($request->filled('search')) {
            $query->where('category', 'like', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('cid')->paginate(10)->appends($request->only(['search']));

        return view('admin.list.bin.categorytrash', compact('categories'));
    }

    public function restore($cid)
    {
        try {
            $category = Category::findOrFail($cid);
            $category->update(['status' => 1]);

            session()->flash('success', 'Category restored successfully.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to restore category.']);
        }
    }

    public function forceDelete($cid)
    {
        try {
            $category = Category::findOrFail($cid);
            $category->delete();

            session()->flash('success', 'Category permanently deleted.');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete category.'
            ]);
        }
    }
}