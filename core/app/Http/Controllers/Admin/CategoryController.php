<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    public function index(Request $request) {

        $pageTitle    = 'All Categories';
        $emptyMessage = 'No category found';
        $categories   = Category::query();

        if ($request->search) {
            $categories->where('name', 'LIKE', "%$request->search%");
        }

        $categories = $categories->latest()->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'emptyMessage', 'categories'));
    }

    public function store(Request $request, $id = 0) {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
        ]);

        if ($id) {
            $category         = Category::findOrFail($request->id);
            $category->status = $request->status ? 1 : 0;
            $notification     = 'Category updated successfully.';
        } else {
            $category     = new Category();
            $notification = 'Category added successfully.';
        }

        $category->name = $request->name;
        $category->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

}
