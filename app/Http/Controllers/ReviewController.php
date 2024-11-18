<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Response;
use App\Http\ResponsePage;
use App\Models\Review;

class ReviewController extends Controller
{
    public function newReview(Request $request)
    {
        $form = $request->validate([
            'product_id' => ['required'],
            'title' => ['required'],
            'content' => ['required'],
            'stars' => ['required'],
        ]);

        $review = new Review();
        $review->user_id = Auth::user()->id;
        $review->product_id = $form['product_id'];
        $review->title = $form['title'];
        $review->content = $form['content'];
        $review->stars = $form['stars'];
        $review->save();

        return response()->json(new Response(0, "OK", null));
    }

    public function listReview(Request $request)
    {
        $productId = $request->query("product_id");
        $pageSize = $request->query("page_size") ?? 10;
        $pageNum = $request->query("page_num") ?? 1;
        
        $query = Review::
            with("reviewer")->
            when($productId, function ($query, $productId) {
                return $query->where('product_id', $productId);
            });
        $total = $query->count();
        $reviews = $query
            ->orderBy("created_at", 'desc')
            ->offset(($pageNum - 1) * $pageSize)
            ->limit($pageSize)
            ->get();
        
        return response()->json(new ResponsePage(0, "OK", $total, $pageSize, $pageNum, $reviews));
    }

}
