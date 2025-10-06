<?php




namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $user = Auth::user();
        $role = $user->role ?? $request->input('role');
        
        // Gender-based greeting
        $greeting = $this->getGenderBasedGreeting($user->sex ?? 'other', $user->name);

        // Send login email notification
        if ($user->email) {
            $subject = 'Login Notification';
            $message = $role === 'seller'
                ? "Dear {$user->name}, you have successfully logged in as a seller."
                : "Dear {$user->name}, you have successfully logged in as a buyer.";
            Mail::raw($message, function ($mail) use ($user, $subject) {
                $mail->to($user->email)
                    ->subject($subject);
            });
        }
        
        if ($role === 'seller') {
            return redirect()->route('seller.dashboard')->with('success', $greeting);
        }
        if ($role === 'buyer') {
            return redirect()->route('home')->with('success', $greeting);
        }
        return redirect()->intended(route('dashboard', absolute: false));

        
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Store a flag before logout to prevent session expiry issues
        $wasLoggedIn = Auth::check();
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear any cached responses
        if ($wasLoggedIn) {
            return redirect('/')->with('success', 'You have been logged out successfully.')
                ->withHeaders([
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0'
                ]);
        }

        return redirect('/');
    }

    /**
     * Get gender-based greeting message
     */
    private function getGenderBasedGreeting(string $gender, string $name): string
    {
        switch ($gender) {
            case 'male':
                return "Welcome back, Mr. {$name}!";
            case 'female':
                return "Welcome back, Ms or Mrs. {$name}!";
            case 'other':
                return "Welcome back, {$name}!";
            default:
                return "Welcome back, {$name}!";
        }
    }
}
