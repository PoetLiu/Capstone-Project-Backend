<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Response;
use App\Http\ResponsePage;
use App\Models\Review;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function getSummary(Request $request)
    {
        $data = [
            "users_count" => User::count(),
            "orders_count" => Order::count(),
            "categories_count" => Category::count(),
            "products_count" => Product::count(),
            "reviews_count" => Review::count(),
        ];

        return response()->json(new Response(0, "OK", $data));
    }
}
