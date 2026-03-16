<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $query->where('category', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status') && in_array($request->status, ['0', '1'])) {
            $query->where('status', $request->status);
        }

        $categories = $query->orderBy('cid')->paginate(15)->appends($request->only(['search', 'status']));

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
        $category = Category::findOrFail($cid);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}