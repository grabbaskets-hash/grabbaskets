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
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        if (! Auth::attempt([$field => $login, 'password' => $this->input('password')], $this->boolean('remember'))) {
            // Attempt to find user in Buyer/Seller tables and materialize into users table
            $buyer = \App\Models\Buyer::where($field, $login)->first();
            $seller = \App\Models\Seller::where($field, $login)->first();

            $record = $buyer ?: $seller;
            $role = $buyer ? 'buyer' : ($seller ? 'seller' : null);

            if ($record && $role && Hash::check($this->input('password'), $record->password)) {
                // Create or update the main users row
                $user = User::updateOrCreate(
                    ['email' => $record->email],
                    [
                        'name' => $record->name,
                        'phone' => $record->phone,
                        'billing_address' => $record->billing_address,
                        'state' => $record->state,
                        'city' => $record->city,
                        'pincode' => $record->pincode,
                        'role' => $role,
                        // Let cast hash the provided password again
                        'password' => $this->input('password'),
                    ]
                );

                // Retry authentication now that users row exists
                if (! Auth::attempt([$field => $login, 'password' => $this->input('password')], $this->boolean('remember'))) {
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages(['login' => trans('auth.failed')]);
                }
            } else {
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages(['login' => trans('auth.failed')]);
            }
        }

        RateLimiter::clear($this->throttleKey());
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
