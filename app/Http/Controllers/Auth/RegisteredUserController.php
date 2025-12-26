<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegisteredUserController extends Controller
{
    /**
     * Show registration form
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'unique:users,phone'],
            'billing_address' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
            'pincode' => ['required'],
            'role' => ['required', 'in:seller,buyer'],
            'sex' => ['required', 'in:male,female,other'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        /*
        |--------------------------------------------------------------------------
        | SELLER REGISTRATION
        |--------------------------------------------------------------------------
        */
        if ($request->role === 'seller') {

            Seller::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'billing_address' => $request->billing_address,
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'sex' => $request->sex,
                'password' => Hash::make($request->password),
            ]);

            $user = User::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'billing_address' => $request->billing_address,
                    'state' => $request->state,
                    'city' => $request->city,
                    'pincode' => $request->pincode,
                    'role' => 'seller',
                    'sex' => $request->sex,
                    'wallet_point' => 0, // ✅ Seller gets 0
                    'password' => Hash::make($request->password),
                ]
            );

            Auth::login($user);

            return redirect()->route('seller.dashboard')->with([
                'success' => $this->greeting($request->sex, $user->name),
                'login_success' => true
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | BUYER REGISTRATION
        |--------------------------------------------------------------------------
        */
        Buyer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'billing_address' => $request->billing_address,
            'state' => $request->state,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'sex' => $request->sex,
            'password' => Hash::make($request->password),
        ]);

        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'billing_address' => $request->billing_address,
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'role' => 'buyer',
                'sex' => $request->sex,
                'wallet_point' => 150, // ✅ Buyer gets 150
                'password' => Hash::make($request->password),
            ]
        );

        Auth::login($user);

        return redirect()->route('home')->with([
            'success' => $this->greeting($request->sex, $user->name),
            'login_success' => true
        ]);
    }

    /**
     * Greeting message
     */
    private function greeting(string $sex, string $name): string
    {
        return "வணக்கம் {$name}! Welcome to GrabBasket family!";
    }
}
