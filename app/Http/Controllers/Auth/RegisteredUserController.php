<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:15', 'unique:users,phone'],
            'billing_address' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:10'],
            'role' => ['required', 'in:seller,buyer'],
            'sex' => ['required', 'in:male,female,other'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.unique' => 'This email is already registered.',
            'phone.unique' => 'This phone number is already registered.',
        ]);

        if ($request->role === 'seller') {
            $seller = \App\Models\Seller::create([
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
            // Also ensure a corresponding users row exists for web auth
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
                    // Let the User model cast hash the password
                    'password' => $request->password,
                ]
            );
            Auth::login($user);
            
            // Gender-based welcome message
            $greeting = $this->getGenderBasedGreeting($request->sex, $user->name);
            return redirect()->route('seller.dashboard')->with([
                'success' => $greeting,
                'tamil_greeting' => true
            ]);
        } else {
            $buyer = \App\Models\Buyer::create([
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
            // Also ensure a corresponding users row exists for web auth
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
                    // Let the User model cast hash the password
                    'password' => $request->password,
                ]
            );
            Auth::login($user);
            
            // Gender-based welcome message
            $greeting = $this->getGenderBasedGreeting($request->sex, $user->name);
            return redirect()->route('home')->with([
                'success' => $greeting,
                'tamil_greeting' => true
            ]);
        }
    }

    /**
     * Get gender-based greeting message
     */
    private function getGenderBasedGreeting(string $gender, string $name): string
    {
        switch ($gender) {
            case 'male':
                return "வணக்கம் {$name}! Welcome to GrabBasket family!";
            case 'female':
                return "வணக்கம் {$name}! Welcome to GrabBasket family!";
            case 'other':
                return "வணக்கம் {$name}! Welcome to GrabBasket family!";
            default:
                return "வணக்கம் {$name}! Welcome to GrabBasket family!";
        }
    }
}
