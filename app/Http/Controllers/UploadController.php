<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Http\Response;
use Illuminate\Http\Request;
 
class UploadController extends Controller
{
    // access uploaded file via: APP_URL/storage/path
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'file' => ['required'],
        ]);
        $path = $request->file('file')->store('avatars');
        return response()->json(new Response(0, "OK", ['path' => $path]));
    }

    public function uploadProduct(Request $request)
    {
        $request->validate([
            'file' => ['required'],
        ]);
        $path = $request->file('file')->store('products');
        return response()->json(new Response(0, "OK", ['path' => $path]));
    }
}