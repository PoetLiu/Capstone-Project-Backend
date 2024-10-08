<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Response;
use App\Models\Product;

class ProductController extends Controller
{
    public function listProduct(Request $request)
    {
        $isFeatured = $request->query("is_featured");
        $isOnsale = $request->query("is_onsale");
        $categoryId = $request->query("category_id");
        $name = $request->query("name");

        $products = DB::table("products")->
            when($isFeatured, function ($query, $isFeatured) {
                return $query->where('is_featured', $isFeatured == "true" ? 1 : 0);
            })->when(
                $isOnsale,
                function ($query, $_) {
                    return $query->whereNotNull('onsale_price');
                }
            )->when(
                $categoryId,
                function ($query, $categoryId) {
                    return $query->where('category_id', $categoryId);
                }
            )->when(
                $name,
                function ($query, $name) {
                    return $query->where('name', 'like', "%".$name."%");
                }
            )
            ->get();
        return response()->json(new Response(0, "OK", $products));
    }

    public function getProduct(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        return response()->json(new Response(0, "OK", $product));
    }
}
