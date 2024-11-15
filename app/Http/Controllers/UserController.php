<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Response;
use App\Models\User;
use App\Models\Address;
use App\Models\Cart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class UserController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $form = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!User::where('email', $form['email'])->exists()) {
            throw new \RuntimeException("Unknown email address.");
        }


        if (Auth::attempt($form)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;
            return response()->json(new Response(0, "OK", ['token' => $token]));
        } else {
            throw new \RuntimeException("The provided credentials do not match our records.");
        }
    }

    public function register(Request $request, $allow_admin = false)
    {
        $form = $request->validate([
            'username' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
            'is_admin' => ['nullable']
        ]);
        if (User::where('email', $form['email'])->exists()) {
            throw new \RuntimeException("User with the email address exists already.");
        }
        $user = new User();
        $user->username = $form['username'];
        $user->email = $form['email'];
        $user->password = Hash::make($form['password']);
        $user->is_admin = $allow_admin ? ($form['is_admin'] == 'true' ? 1 : 0) : 0;
        $user->save();

        $cart = new Cart();
        $cart->user_id = $user->id;
        $cart->save();
        return response()->json(new Response(0, "OK", null));
    }

    public function add(Request $request) {
        return $this->register($request, true);
    }

    public function getProfile(Request $request)
    {
        $profile = $request->user();
        if ($profile->shipping_address_id != null)
            $profile->shipping_address = Address::find($profile->shipping_address_id);
        if ($profile->billing_address_id!= null)
            $profile->billing_address = Address::find($profile->billing_address_id);
        return response()->json(new Response(0, "OK", $profile));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(new Response(0, "OK", $request->user()));
    }

    public function forgotPassword(Request $request)
    {
        $form = $request->validate(['email' => 'required|email']);

        if (!User::where('email', $form['email'])->exists()) {
            throw new \RuntimeException("Unknown email, please check your input or register a new account.");
        }
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return response()->json(
            new Response($status === Password::RESET_LINK_SENT ? 0 : 1, "OK", $request->user()));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
        if ($status != Password::PASSWORD_RESET)
            throw new RuntimeException("Reset Passowrd failed");
     
        return response()->json( 
            new Response(0, $status, $request->user())
        );
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]); 

        if(!Hash::check($request->old_password, $request->user()->password)){
            throw new \RuntimeException("Old Password Doesn't match!");
        }
        $request->user()->update([
            'password' => Hash::make($request->new_password)
        ]); 
        return response()->json( 
            new Response(0, "OK", $request->user())
        );
    }

    public function updateBasic(Request $request)
    {
        $form = $request->validate([
            'username' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
        ]);

        $user = Auth::user();
        $user->username = $form['username'];
        $user->email = $form['email'];
        $user->phone = $form['phone'];
        $user->save();
        return response()->json(new Response(0, "OK", null));
    }
    
    public function updateBillingAddress(Request $request)
    {
        return $this->updateAddress($request, "billing_address_id");
    }
    public function updateShippingAddress(Request $request)
    {
        return $this->updateAddress($request, "shipping_address_id");
    }

    public function updateAddress(Request $request, $filed)
    {
        $form = $request->validate([
            'firstname' => ['required'],
            'lastname' => ['required'],
            'address' => ['required'],
            'city' => ['required'],
            'province_id' => ['required'],
            'postcode' => ['required'],
            'phone' => ['required'],
            'id' => [],
        ]);

        $user = Auth::user();
        $addr = $user[$filed] == null ? new Address() : Address::find($user[$filed]);
        $addr->firstname = $form['firstname'];
        $addr->lastname= $form['lastname'];
        $addr->address= $form['address'];
        $addr->city= $form['city'];
        $addr->province_id= $form['province_id'];
        $addr->postcode= $form['postcode'];
        $addr->phone= $form['phone'];
        $addr->user_id = $user->id;
        $addr->save();
        if ($user[$filed] == null) {
            $user[$filed] = $addr->id;
            $user->save();
        } 

        return response()->json(new Response(0, "OK", null));
    }

    public function list(Request $request)
    {
        $name = $request->query("username");
        $users = User::
           when(
                $name,
                function ($query, $name) {
                    return $query->where('username', 'like', "%".$name."%");
                }
            )
            ->get();
        return response()->json(new Response(0, "OK", $users));
    }
}
