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
            when($isFeatured , function ($query, $isFeatured) {
                return $query->where('is_featured', $isFeatured == "true" ? 1 : 0);
            })->when(
                $isOnsale,
                function ($query, $isOnsale) {
                    return $isOnsale == "true" ? $query->whereNotNull('onsale_price') : $query->whereNull('onsale_price');
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

    public function addProduct(Request $request)
    {
        $form = $request->validate([
            'brand' => ['required'],
            'name' => ['required'],
            'description' => ['required'],
            'specifications' => ['required'],
            'price' => ['required'],
            'onsale_price' => [''],
            'stock' => [''],
            'is_featured' => [''],
            'category_id' => ['required'],
            'image_url' => ['required'],
        ]);
        if (Product::where('name', $form['name'])->exists()) {
            throw new \RuntimeException("Product with the name exists already.");
        }

        $p = new Product();
        $p->brand = $form['brand'];
        $p->name = $form['name'];
        $p->description = $form['description'];
        $p->specifications = $form['specifications'];
        $p->price = $form['price'];
        $p->onsale_price = $form['onsale_price'];
        $p->stock = $form['stock'];
        $p->is_featured = $form['is_featured'] == 'true' ? 1 : 0;
        $p->category_id = $form['category_id'];
        $p->image_url = $form['image_url'];
        $p->save();

        return response()->json(new Response(0, "OK", $p));
    }

    public function editProduct(Request $request, $id)
    {
        $form = $request->validate([
            'brand' => ['required'],
            'name' => ['required'],
            'description' => ['required'],
            'specifications' => ['required'],
            'price' => ['required'],
            'onsale_price' => [''],
            'stock' => [''],
            'is_featured' => [''],
            'category_id' => ['required'],
            'image_url' => ['required'],
        ]);
        if (Product::where("id", $id)->doesntExist()) {
            throw new \RuntimeException("Product with the id doens't exist.");
        }

        if (Product::where('name', $form['name'])->where('id', '!=', $id)->exists()) {
            throw new \RuntimeException("Product with the name exists already.");
        }

        $p = Product::find($id);
        $p->brand = $form['brand'];
        $p->name = $form['name'];
        $p->description = $form['description'];
        $p->specifications = $form['specifications'];
        $p->price = $form['price'];
        $p->onsale_price = $form['onsale_price'];
        $p->stock = $form['stock'];
        $p->is_featured = $form['is_featured'] == 'true' ? 1 : 0;
        $p->category_id = $form['category_id'];
        $p->image_url = $form['image_url'];
        $p->save();

        return response()->json(new Response(0, "OK", null));
    }

    public function deleteProduct(Request $request, $id)
    {
        if (Product::where("id", $id)->doesntExist()) {
            throw new \RuntimeException("Product with the id doens't exist.");
        }

        Product::where("id", $id)->delete();
        return response()->json(new Response(0, "OK", null));
    }
}
