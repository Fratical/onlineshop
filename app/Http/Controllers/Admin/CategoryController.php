<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
//        dd($request->all());
        $validateData = $request->validate([
            "name" => "required|max:50|unique:categories",
            "slug" => "required|max:50|unique:categories",
            "status" => "nullable",
            "image" => "required|image|mimes:jpg,png,svg",
        ]);

        if ($request->hasFile('image'))
        {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext; //147785.png
            $file->move('uploads/category/', $filename);
            $validateData['image'] = 'uploads/category/'. $filename;
        }

        $validateData['status'] = $request->status == true ? '1':'0';

        Category::create([
           'name' => $validateData['name'],
           'slug' => $validateData['slug'],
           'status' => $validateData['status'],
           'image' => $validateData['image']
        ]);

        return redirect('admin/category')->with('message','Category Created Successfully');





    }


}
