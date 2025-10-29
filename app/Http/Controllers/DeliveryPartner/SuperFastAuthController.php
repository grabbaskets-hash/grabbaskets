<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class SuperFastAuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View
    {
        return view('delivery-partner.auth.login');
    }

    /**
     * Super-fast login with minimal overhead
     */
    public function login(Request $request): RedirectResponse
    {
        $startTime = microtime(true);
        
        // Basic validation only
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = trim($request->input('login'));
        $password = $request->input('password');
        
        // Determine login field and normalize
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
        $loginField = $isEmail ? 'email' : 'phone';
        
        if (!$isEmail) {
            $login = preg_replace('/\D/', '', $login); // Remove non-digits for phone
        } else {
            $login = strtolower($login); // Normalize email
        }

        // SUPER FAST: Direct database query with minimal fields
        $queryStartTime = microtime(true);
        $partner = DB::table('delivery_partners')
            ->select(['id', 'name', 'email', 'phone', 'password', 'status'])
            ->where($loginField, $login)
            ->first();
        $queryTime = (microtime(true) - $queryStartTime) * 1000;

        if (!$partner) {
            Log::warning("DeliveryPartner Login Failed - User Not Found", [
                'login_field' => $loginField,
                'query_time_ms' => round($queryTime, 2),
                'total_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
                'ip' => $request->ip()
            ]);
            return back()->withErrors(['login' => 'Invalid credentials.']);
        }

        // SUPER FAST: Password verification
        $passwordStartTime = microtime(true);
        if (!Hash::check($password, $partner->password)) {
            $passwordTime = (microtime(true) - $passwordStartTime) * 1000;
            Log::warning("DeliveryPartner Login Failed - Invalid Password", [
                'partner_id' => $partner->id,
                'query_time_ms' => round($queryTime, 2),
                'password_time_ms' => round($passwordTime, 2),
                'total_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
                'ip' => $request->ip()
            ]);
            return back()->withErrors(['login' => 'Invalid credentials.']);
        }
        $passwordTime = (microtime(true) - $passwordStartTime) * 1000;

        // SUPER FAST: Status check
        if (!in_array($partner->status, ['active', 'pending'])) {
            $messages = [
                'rejected' => 'Your account has been rejected. Please contact support.',
                'suspended' => 'Your account has been suspended. Please contact support.',
                'inactive' => 'Your account is inactive. Please contact support.',
            ];
            return back()->withErrors(['login' => $messages[$partner->status] ?? 'Account status issue.']);
        }

        // SUPER FAST: Login using ID (fastest method)
        $authStartTime = microtime(true);
        Auth::guard('delivery_partner')->loginUsingId($partner->id, $request->boolean('remember'));
        $authTime = (microtime(true) - $authStartTime) * 1000;

        // SUPER FAST: Session regeneration
        $sessionStartTime = microtime(true);
        $request->session()->regenerate();
        $sessionTime = (microtime(true) - $sessionStartTime) * 1000;

        $totalTime = (microtime(true) - $startTime) * 1000;

        // Log success
        Log::info("DeliveryPartner Login Success - SUPER FAST", [
            'partner_id' => $partner->id,
            'partner_name' => $partner->name,
            'login_field' => $loginField,
            'status' => $partner->status,
            'query_time_ms' => round($queryTime, 2),
            'password_time_ms' => round($passwordTime, 2),
            'auth_time_ms' => round($authTime, 2),
            'session_time_ms' => round($sessionTime, 2),
            'total_time_ms' => round($totalTime, 2),
            'ip' => $request->ip()
        ]);

        // Handle status-specific redirects
        if ($partner->status === 'pending') {
            return redirect()
                ->route('delivery-partner.dashboard')
                ->with('warning', 'Your account is under review. You will be notified once approved.');
        }

        return redirect()
            ->intended(route('delivery-partner.dashboard'))
            ->with('success', 'Welcome back, ' . $partner->name . '!');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('delivery_partner')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('delivery-partner.login')
            ->with('success', 'Logged out successfully.');
    }
}