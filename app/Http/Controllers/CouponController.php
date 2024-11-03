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
            'coupon_code' => ['required'],
        ]);

        $c = Coupon::where("code", $form["coupon_code"])->first();
        if ($c == null)
            throw new RuntimeException("Unknown coupon code.");

        return response()->json(new Response(0, "OK", $c));
    }
}
