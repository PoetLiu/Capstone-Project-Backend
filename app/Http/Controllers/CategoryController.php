<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Response;
use App\Models\Product;

class CategoryController extends Controller
{
    public function listCategory(Request $request)
    {
        $categories = DB::table("categories")->get();
        return response()->json(new Response(0, "OK", $categories));
    }
}
