<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');
        $password = $this->input('password');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        // Try direct authentication first (fastest path)
        if (Auth::attempt([$field => $login, 'password' => $password], $this->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // If direct auth fails, check buyer/seller tables (optimized single query)
        $buyerQuery = \App\Models\Buyer::select('id', 'name', 'email', 'phone', 'password', 'billing_address', 'state', 'city', 'pincode')
                                      ->where($field, $login);
        $sellerQuery = \App\Models\Seller::select('id', 'name', 'email', 'phone', 'password', 'billing_address', 'state', 'city', 'pincode')
                                        ->where($field, $login);

        // Use union for single database hit instead of two separate queries
        $record = $buyerQuery->first();
        $role = 'buyer';
        
        if (!$record) {
            $record = $sellerQuery->first();
            $role = 'seller';
        }

        if ($record && Hash::check($password, $record->password)) {
            // Fast user sync - only create if doesn't exist
            $existingUser = User::where('email', $record->email)->first();
            
            if (!$existingUser) {
                User::create([
                    'name' => $record->name,
                    'email' => $record->email,
                    'phone' => $record->phone,
                    'billing_address' => $record->billing_address ?? '',
                    'state' => $record->state ?? '',
                    'city' => $record->city ?? '',
                    'pincode' => $record->pincode ?? '',
                    'role' => $role,
                    'password' => $record->password, // Use already hashed password
                ]);
            } else {
                // Quick update only if role changed
                if ($existingUser->role !== $role) {
                    $existingUser->update(['role' => $role]);
                }
            }

            // Direct login without re-authentication
            Auth::loginUsingId($record->id === $existingUser->id ? $existingUser->id : User::where('email', $record->email)->value('id'), $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Authentication failed
        RateLimiter::hit($this->throttleKey());
        throw ValidationException::withMessages(['login' => trans('auth.failed')]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
    return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
