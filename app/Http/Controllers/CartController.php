<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Response;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use RuntimeException;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $form = $request->validate([
            'product_id' => ['required'],
        ]);

        $cart = Cart::where("user_id", $request->user()->id)->first();
        $product = Product::find($form['product_id']);
        if (!$product) {
            return throw new RuntimeException('Product not found.');
        }

        $cartItem = CartItem::where("cart_id", $cart->id)
            ->where("product_id", $product->id)
            ->first();
        if (!$cartItem) {
            $cartItem = new CartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->product_id = $product->id;
            $cartItem->quantity = 1;
            $cartItem->price = $product->price;
        } else {
            $cartItem->quantity++;
        }
        $cartItem->save();

        return response()->json(new Response(0, "OK", null));
    }

    public function listCart(Request $request)
    {
        $cart = Cart::where("user_id", $request->user()->id)->first();
        $cartItems = CartItem::where("cart_id", $cart->id)->get();
        return response()->json(new Response(0, "OK", $cartItems));
    }

    public function editCartItem(Request $request, string $id) {
        $form = $request->validate([
            'quantity' => ['required'],
        ]);

        $cart = Cart::where("user_id", $request->user()->id)->first();
        $cartItem = CartItem::find($id);
        if (!$cartItem) {
            return new RuntimeException("Unknown cart item");
        }

        $cartItem->quantity = $form["quantity"];
        $cartItem->save();

        return response()->json(new Response(0, "OK", null));
    }

    public function removeCartItem(Request $request, string $id) {

       CartItem::where("id", $id)->delete();
        return response()->json(new Response(0, "OK", null));
    }
}
