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
use RuntimeException;

class CouponController extends Controller
{
    public function validate(Request $request)
    {
        $form = $request->validate([
            'code' => ['required'],
        ]);

        $c = Coupon::where("code", $form["code"])->first();
        if ($c == null)
            throw new RuntimeException("Unknown coupon code.");

        return response()->json(new Response(0, "OK", $c));
    }

    public function create(Request $request)
    {
        $form = $request->validate([
            'code' => ['required'],
            'min_amount' => ['required', 'integer'],
            'discount' => ['required', 'integer'],
        ]);

        $c = Coupon::where("code", $form["code"])->first();
        if ($c != null)
            throw new RuntimeException("Coupon already exists with the same code.");

        $c = new Coupon();
        $c->code = $form["code"];
        $c->min_amount = $form["min_amount"];
        $c->discount= $form["discount"];
        $c->save();
        
        return response()->json(new Response(0, "OK", $c));
    }

    public function edit(Request $request, String $id)
    {
        $form = $request->validate([
            'code' => ['required'],
            'min_amount' => ['required', 'integer'],
            'discount' => ['required', 'integer'],
        ]);

        $c = Coupon::find($id);
        if ($c == null)
            throw new RuntimeException("Unknown coupon code.");

        $c->code = $form["code"];
        $c->min_amount = $form["min_amount"];
        $c->discount= $form["discount"];
        $c->save();
        
        return response()->json(new Response(0, "OK", $c));
    }

    public function list(Request $request)
    {
        $list = DB::table("coupons")->get();
        return response()->json(new Response(0, "OK", $list));
    }
}
