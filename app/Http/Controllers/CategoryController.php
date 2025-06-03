<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ]);


        $category = Category::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);
        if (!$category)
        {
            return redirect()->back()->with('error', 'Failed to create category. Please try again.');
        } else
        {
            return redirect()->route('inventory')->with('success', 'Category created successfully!');
        }


    }
}
