<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Response;
use App\Models\Address;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\OrderItem;
use App\Notifications\OrderConfirmed;
use RuntimeException;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $form = $request->validate([
            'include_gift' => ['required'],
            'coupon_id' => [''],
            'shipping_address_id' => [''],
            'billing_address_id' => [''],
        ]);

        $order = new Order();
        if ($form['shipping_address_id'] == null) {
            $address = $this->createAddress($request, "shipping_address_");
            $order->shipping_address_id = $address->id;
        } else {
            $order->shipping_address_id = $form['shipping_address_id'];
        }

        if ($form['billing_address_id'] == null) {
            $address = $this->createAddress($request, "billing_address_");
            $order->billing_address_id = $address->id;
        } else {
            $order->billing_address_id = $form['billing_address_id'];
        }

        $user = Auth::user();
        $cart = Cart::where("user_id", $user->id)->first();
        $cartItems = CartItem::with("product")->where("cart_id", $cart->id)->get();
        $itemsTotalAmount = 0;
        $itemsDiscountAmount = 0;
        foreach($cartItems as $cartItem) {
            $itemsTotalAmount += $cartItem->quantity * $cartItem->price;
            if ($cartItem->product->onsale_price != null)
                $itemsDiscountAmount += $cartItem->product->price - $cartItem->product->onsale_price;
        }
        if ($form['coupon_id'] != null) {
            $coupon = Coupon::find($form['coupon_id']);
            if ($itemsTotalAmount > $coupon->min_amount) {
                $itemsDiscountAmount += $coupon->discount;
            }
        }
        $taxAmount = ($itemsTotalAmount - $itemsDiscountAmount) * 0.13;
        $shippingAmount = ($itemsTotalAmount - $itemsDiscountAmount) * 0.05;
        $order->user_id = $user->id;
        $order->coupon_id = $form['coupon_id'];
        $order->include_gift = $form['include_gift'] == "true" ? 1 : 0;
        $order->items_total_amount = $itemsTotalAmount;
        $order->items_discount_amount = $itemsDiscountAmount;
        $order->tax_amount = $taxAmount;
        $order->shipping_amount = $shippingAmount;
        $order->total_amount = $itemsTotalAmount - $itemsDiscountAmount + $taxAmount + $shippingAmount;
        $order->save();

        foreach($cartItems as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $cartItem->product_id;
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->price = $cartItem->price;
            $orderItem->save();
        }

        $user->notify((new OrderConfirmed($order))->afterCommit());
        return response()->json(new Response(0, "OK", null));
    }

    private function createAddress(Request $request, $prefix)
    {
        $form = $request->validate([
            $prefix . 'firstname' => ['required'],
            $prefix . 'lastname' => ['required'],
            $prefix . 'address' => ['required'],
            $prefix . 'city' => ['required'],
            $prefix . 'province_id' => ['required'],
            $prefix . 'postcode' => ['required'],
            $prefix . 'phone' => ['required'],
        ]);

        $user = Auth::user();
        $addr = new Address();
        $addr->firstname = $form['firstname'];
        $addr->lastname= $form['lastname'];
        $addr->address= $form['address'];
        $addr->city= $form['city'];
        $addr->province_id= $form['province_id'];
        $addr->postcode= $form['postcode'];
        $addr->phone= $form['phone'];
        $addr->user_id = $user->id;
        $addr->save();
        return $addr;
    }
}
