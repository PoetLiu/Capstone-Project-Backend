<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Response;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function listCategory(Request $request)
    {
        $categories = DB::table("categories")->get();
        return response()->json(new Response(0, "OK", $categories));
    }

    public function addCategory(Request $request)
    {
        $form = $request->validate([
            'name' => ['required'],
            'icon' => ['required'],
        ]);
        if (Category::where('name', $form['name'])->exists()) {
            throw new \RuntimeException("Category with the name exists already.");
        }

        $cat = new Category();
        $cat->name = $form['name'];
        $cat->icon = $form['icon'];
        $cat->save();

        return response()->json(new Response(0, "OK", null));
    }

    public function editCategory(Request $request, String $id)
    {
        $form = $request->validate([
            'name' => ['required'],
            'icon' => ['required'],
        ]);
        if (Category::where('id', "!=", $id)->where('name', $form['name'])->exists()) {
            throw new \RuntimeException("Category with the name exists already.");
        }

        $cat = Category::find($id);
        $cat->name = $form['name'];
        $cat->icon = $form['icon'];
        $cat->save();

        return response()->json(new Response(0, "OK", null));
    }

    public function deleteCategory(Request $request, String $id)
    {
        if (Category::where("id", $id)->doesntExist()) {
            throw new \RuntimeException("Category with the id dosen't exist.");
        }

        if (Product::where("category_id", $id)->exists()) {
            throw new \RuntimeException("The category is not empty, please remove products first.");
        }

        Category::where("id", $id)->delete();
        return response()->json(new Response(0, "OK", null));
    }
}
