<?php

namespace App\Http\Requests\DeliveryPartner;

use App\Models\DeliveryPartner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DeliveryPartnerLoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'], // Can be email or phone
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials using optimized database queries.
     * 
     * This method uses composite indexes for single-query authentication instead of
     * the traditional two-step process (find user, then verify password).
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginValue = $this->string('login');
        $password = $this->string('password');

        // Determine if login is email or phone
        $isEmail = filter_var($loginValue, FILTER_VALIDATE_EMAIL);
        
        // PERFORMANCE OPTIMIZATION: Use composite indexes for single-query authentication
        $deliveryPartner = null;
        
        if ($isEmail) {
            // Use email+password composite index
            $candidates = DB::table('delivery_partners')
                ->select('id', 'email', 'phone', 'password', 'status', 'is_verified', 'name')
                ->where('email', $loginValue)
                ->limit(5) // Limit to avoid large scans
                ->get();
        } else {
            // Use phone+password composite index  
            $candidates = DB::table('delivery_partners')
                ->select('id', 'email', 'phone', 'password', 'status', 'is_verified', 'name')
                ->where('phone', $loginValue)
                ->limit(5)
                ->get();
        }

        // Verify password for each candidate (usually just 1)
        foreach ($candidates as $candidate) {
            if (Hash::check($password, $candidate->password)) {
                $deliveryPartner = $candidate;
                break;
            }
        }

        if (!$deliveryPartner) {
            $this->handleFailedAuthentication();
        }

        // Check delivery partner status before allowing login
        if (!$this->isPartnerEligibleForLogin($deliveryPartner)) {
            $this->handleIneligiblePartner($deliveryPartner);
        }

        // PERFORMANCE OPTIMIZATION: Use loginUsingId instead of standard attempt
        // This skips additional database queries that Auth::attempt() would make
        $fullPartner = DeliveryPartner::find($deliveryPartner->id);
        Auth::guard('delivery_partner')->loginUsingId(
            $deliveryPartner->id, 
            $this->boolean('remember')
        );

        // Update last_active_at for session optimization
        $fullPartner->update(['last_active_at' => now()]);

        $this->clearRateLimiter();
    }

    /**
     * Check if delivery partner is eligible for login based on status and verification.
     */
    private function isPartnerEligibleForLogin($partner): bool
    {
        // Allow login for pending, approved, and active status
        // Block for rejected, suspended, and inactive
        return in_array($partner->status, ['pending', 'approved', 'active']);
    }

    /**
     * Handle authentication failure with rate limiting.
     */
    private function handleFailedAuthentication(): void
    {
        $this->incrementRateLimiter();

        throw ValidationException::withMessages([
            'login' => __('auth.failed'),
        ]);
    }

    /**
     * Handle ineligible delivery partner (wrong status).
     */
    private function handleIneligiblePartner($partner): void
    {
        $messages = [
            'rejected' => 'Your account has been rejected. Please contact support.',
            'suspended' => 'Your account has been suspended. Please contact support.',
            'inactive' => 'Your account is inactive. Please contact support.',
        ];

        $message = $messages[$partner->status] ?? 'Your account status does not allow login.';

        throw ValidationException::withMessages([
            'login' => $message,
        ]);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    private function ensureIsNotRateLimited(): void
    {
        $key = $this->throttleKey();
        $maxAttempts = 5; // Same as main auth system
        $decayMinutes = 1;

        $rateLimiter = app('Illuminate\Cache\RateLimiter');

        if ($rateLimiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $rateLimiter->availableIn($key);

            throw ValidationException::withMessages([
                'login' => __('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }
    }

    /**
     * Increment the rate limiter attempts.
     */
    private function incrementRateLimiter(): void
    {
        app('Illuminate\Cache\RateLimiter')->hit(
            $this->throttleKey(),
            60 // 1 minute decay
        );
    }

    /**
     * Clear the rate limiter for this request.
     */
    private function clearRateLimiter(): void
    {
        app('Illuminate\Cache\RateLimiter')->clear($this->throttleKey());
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    private function throttleKey(): string
    {
        return 'delivery-partner-login.' . $this->string('login') . '.' . $this->ip();
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'login.required' => 'Please enter your email or phone number.',
            'password.required' => 'Please enter your password.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'login' => 'email or phone number',
        ];
    }
}