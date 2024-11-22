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
    private $stripe;
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));
    }

    public function checkout(Request $request)
    {
        $form = $request->validate([
            'include_gift' => ['required'],
            'coupon_id' => ['nullable'],
            'shipping_address_id' => ['nullable'],
            'billing_address_id' => ['nullable'],
        ]);

        $order = new Order();
        if (!$request->has('shipping_address_id')) {
            $address = $this->createAddress($request, "shipping_address_");
            $order->shipping_address_id = $address->id;
        } else {
            $order->shipping_address_id = $form['shipping_address_id'];
        }

        if (!$request->has('billing_address_id')) {
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
        foreach ($cartItems as $cartItem) {
            $itemsTotalAmount += $cartItem->quantity * $cartItem->price;
            if ($cartItem->product->onsale_price != null)
                $itemsDiscountAmount += $cartItem->product->price - $cartItem->product->onsale_price;
        }
        if ($request->has('coupon_id')) {
            $coupon = Coupon::find($form['coupon_id']);
            if ($itemsTotalAmount > $coupon->min_amount) {
                $itemsDiscountAmount += $coupon->discount;
            }
            $order->coupon_id = $form['coupon_id'];
        }
        $taxAmount = ($itemsTotalAmount - $itemsDiscountAmount) * 0.13;
        $shippingAmount = ($itemsTotalAmount - $itemsDiscountAmount) * 0.05;
        $order->user_id = $user->id;
        $order->include_gift = $form['include_gift'] == "true" ? 1 : 0;
        $order->items_total_amount = $itemsTotalAmount;
        $order->items_discount_amount = $itemsDiscountAmount;
        $order->tax_amount = $taxAmount;
        $order->shipping_amount = $shippingAmount;
        $order->total_amount = $itemsTotalAmount - $itemsDiscountAmount + $taxAmount + $shippingAmount;
        $order->save();

        foreach ($cartItems as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $cartItem->product_id;
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->price = $cartItem->price;
            $orderItem->save();
        }
        // clear cart
        CartItem::where("cart_id", $cart->id)->delete();
        $user->notify((new OrderConfirmed($order))->afterCommit());
        return $this->stripeCheckout($cartItems);
    }

    private function createStripePrices($stripe, $cartItems)
    {
        $lineItems = array();
        foreach ($cartItems as $cartItem) {
            $product = $stripe->products->create(['name' => $cartItem->product->name]);
            $price = $stripe->prices->create([
                'product' => "$product->id",
                'unit_amount' => $cartItem->product->price * 100,
                'currency' => 'cad',
            ]);

            $lineItems[] = [
                'price' => "$price->id",
                'quantity' => $cartItem->quantity,
            ];
        }
        return $lineItems;
    }

    private function stripeCheckout($cartItems)
    {
        $lineItems = $this->createStripePrices($this->stripe, $cartItems);
        $YOUR_DOMAIN = env("UI_URL");
        $checkout_session = $this->stripe->checkout->sessions->create([
            'ui_mode' => 'embedded',
            'line_items' => $lineItems,
            'mode' => 'payment',
            'return_url' => $YOUR_DOMAIN . '/return?session_id={CHECKOUT_SESSION_ID}',
        ]);

        return response()->json(new Response(0, "OK", ['clientSecret' => $checkout_session->client_secret]));
    }

    public function getCheckoutStatus(Request $request)
    {
        $form = $request->validate([
            'session_id' => ['required'],
        ]);

        $session = $this->stripe->checkout->sessions->retrieve($form["session_id"]);
        return response()->json(
            new Response(
                0,
                "OK",
                ['status' => $session->status, 'customer_email' => $session->customer_details->email]
            )
        );
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
        $addr->firstname = $form[$prefix . 'firstname'];
        $addr->lastname = $form[$prefix . 'lastname'];
        $addr->address = $form[$prefix . 'address'];
        $addr->city = $form[$prefix . 'city'];
        $addr->province_id = $form[$prefix . 'province_id'];
        $addr->postcode = $form[$prefix . 'postcode'];
        $addr->phone = $form[$prefix . 'phone'];
        $addr->user_id = $user->id;
        $addr->save();
        return $addr;
    }

    public function listOrder(Request $request)
    {
        $userId = $request->query("user_id");
        $orders = DB::table("orders")->when($userId, function ($query, $userId) {
            return $query->where('user_id', $userId);
        })
            ->get();
        return response()->json(new Response(0, "OK", $orders));
    }
}
