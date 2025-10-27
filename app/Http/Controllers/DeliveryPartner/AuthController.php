<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegisterForm(): View
    {
        return view('delivery-partner.auth.register');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm(): View
    {
        return view('delivery-partner.auth.login');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:delivery_partners,email',
            'phone' => 'required|string|size:10|unique:delivery_partners,phone',
            'password' => 'required|string|min:6|confirmed',
            'alternate_phone' => 'nullable|string|size:10',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|size:6',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            
            // Vehicle Information
            'vehicle_type' => 'required|in:bike,scooter,bicycle,car,auto',
            'vehicle_number' => 'required|string|max:20|unique:delivery_partners,vehicle_number',
            'license_number' => 'required|string|max:50|unique:delivery_partners,license_number',
            'license_expiry' => 'required|date|after:today',
            'vehicle_rc_number' => 'nullable|string|max:50',
            'insurance_number' => 'nullable|string|max:50',
            'insurance_expiry' => 'nullable|date|after:today',
            
            // Documents
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'license_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'vehicle_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_number' => 'required|string|size:12|unique:delivery_partners,aadhar_number',
            'aadhar_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'pan_number' => 'nullable|string|size:10',
            'pan_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Bank Details
            'bank_account_holder' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:20',
            'bank_ifsc_code' => 'nullable|string|max:11',
            'bank_name' => 'nullable|string|max:255',
            
            // Emergency Contact
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|size:10',
            'emergency_contact_relation' => 'nullable|string|max:100',
            
            // Terms
            'terms_accepted' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Prepare data for creation
            $data = $validator->validated();
            $data['password'] = Hash::make($data['password']);
            $data['status'] = 'pending';
            $data['is_verified'] = false;
            $data['is_online'] = false;
            $data['is_available'] = false;

            // Remove terms_accepted as it's not in the model
            unset($data['terms_accepted']);

            // Handle file uploads
            $uploadedFiles = [];
            $fileFields = [
                'profile_photo',
                'license_photo', 
                'vehicle_photo',
                'aadhar_photo',
                'pan_photo'
            ];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = 'delivery-partner/' . $field . '/' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // Store in public disk first, then sync to R2 if available
                    $path = $file->storeAs('', $filename, 'public');
                    $data[$field] = $filename;
                    $uploadedFiles[] = $filename;
                    
                    // Try to sync to R2 storage
                    try {
                        if (Storage::disk('r2')->exists($filename)) {
                            Storage::disk('r2')->delete($filename);
                        }
                        Storage::disk('r2')->put($filename, $file->get());
                    } catch (\Exception $e) {
                        // R2 upload failed, but continue with local storage
                        logger('R2 upload failed for delivery partner document: ' . $e->getMessage());
                    }
                }
            }

            // Create delivery partner
            $deliveryPartner = DeliveryPartner::create($data);

            // Send notification to admin about new registration
            // $this->notifyAdminNewRegistration($deliveryPartner);

            return redirect()
                ->route('delivery-partner.login')
                ->with('success', 'Registration successful! Your application is under review. You will receive an email once approved.');

        } catch (\Exception $e) {
            // Clean up uploaded files if creation fails
            foreach ($uploadedFiles as $filename) {
                try {
                    Storage::disk('public')->delete($filename);
                    Storage::disk('r2')->delete($filename);
                } catch (\Exception $deleteError) {
                    // Ignore cleanup errors
                }
            }

            logger('Delivery Partner Registration Error: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Handle login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => 'required|string', // Can be email or phone
            'password' => 'required|string',
        ]);

        // Determine if login is email or phone
        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        $loginData = [
            $loginField => $credentials['login'],
            'password' => $credentials['password']
        ];

        if (Auth::guard('delivery_partner')->attempt($loginData, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $partner = Auth::guard('delivery_partner')->user();
            
            // Update last active timestamp
            $partner->update(['last_active_at' => now()]);
            
            // Check partner status
            if ($partner->status === 'pending') {
                return redirect()
                    ->route('delivery-partner.dashboard')
                    ->with('warning', 'Your account is still under review. You will be notified once approved.');
            } elseif ($partner->status === 'rejected') {
                Auth::guard('delivery_partner')->logout();
                return back()
                    ->withErrors(['login' => 'Your account has been rejected. Please contact support.']);
            } elseif ($partner->status === 'suspended') {
                Auth::guard('delivery_partner')->logout();
                return back()
                    ->withErrors(['login' => 'Your account has been suspended. Please contact support.']);
            } elseif ($partner->status === 'inactive') {
                Auth::guard('delivery_partner')->logout();
                return back()
                    ->withErrors(['login' => 'Your account is inactive. Please contact support.']);
            }

            return redirect()
                ->intended(route('delivery-partner.dashboard'))
                ->with('success', 'Welcome back, ' . $partner->name . '!');
        }

        return back()
            ->withErrors(['login' => 'Invalid credentials.'])
            ->onlyInput('login');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request): RedirectResponse
    {
        $partner = Auth::guard('delivery_partner')->user();
        
        if ($partner) {
            // Mark as offline when logging out
            $partner->update([
                'is_online' => false,
                'is_available' => false
            ]);
        }

        Auth::guard('delivery_partner')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('delivery-partner.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show profile page.
     */
    public function profile(): View
    {
        $partner = Auth::guard('delivery_partner')->user();
        return view('delivery-partner.auth.profile', compact('partner'));
    }

    /**
     * Update profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $partner = Auth::guard('delivery_partner')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:delivery_partners,email,' . $partner->id,
            'phone' => 'required|string|size:10|unique:delivery_partners,phone,' . $partner->id,
            'alternate_phone' => 'nullable|string|size:10',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|size:6',
            'vehicle_number' => 'required|string|max:20|unique:delivery_partners,vehicle_number,' . $partner->id,
            'license_expiry' => 'required|date|after:today',
            'insurance_expiry' => 'nullable|date|after:today',
            'bank_account_holder' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:20',
            'bank_ifsc_code' => 'nullable|string|max:11',
            'bank_name' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|size:10',
            'emergency_contact_relation' => 'nullable|string|max:100',
            'max_delivery_distance' => 'nullable|integer|min:1|max:50',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $validator->validated();

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($partner->profile_photo) {
                    try {
                        Storage::disk('public')->delete($partner->profile_photo);
                        Storage::disk('r2')->delete($partner->profile_photo);
                    } catch (\Exception $e) {
                        // Ignore deletion errors
                    }
                }

                $file = $request->file('profile_photo');
                $filename = 'delivery-partner/profile_photo/' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                $file->storeAs('', $filename, 'public');
                $data['profile_photo'] = $filename;
                
                // Try to sync to R2
                try {
                    Storage::disk('r2')->put($filename, $file->get());
                } catch (\Exception $e) {
                    logger('R2 upload failed for delivery partner profile photo: ' . $e->getMessage());
                }
            }

            $partner->update($data);

            return back()->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            logger('Delivery Partner Profile Update Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Change password.
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $partner = Auth::guard('delivery_partner')->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        if (!Hash::check($request->current_password, $partner->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $partner->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Toggle online/offline status.
     */
    public function toggleOnlineStatus(Request $request)
    {
        $partner = Auth::guard('delivery_partner')->user();

        if (!$partner->isAvailableForDelivery() && !$partner->is_online) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot go online. Please ensure your account is approved and verified.'
            ]);
        }

        if ($partner->is_online) {
            $partner->goOffline();
            $message = 'You are now offline';
        } else {
            $partner->goOnline();
            $message = 'You are now online';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_online' => $partner->is_online,
            'is_available' => $partner->is_available
        ]);
    }

    /**
     * Toggle availability status.
     */
    public function toggleAvailability(Request $request)
    {
        $partner = Auth::guard('delivery_partner')->user();

        if (!$partner->is_online) {
            return response()->json([
                'success' => false,
                'message' => 'You must be online to change availability.'
            ]);
        }

        $partner->toggleAvailability();

        $message = $partner->is_available ? 'You are now available for deliveries' : 'You are now busy';

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_available' => $partner->is_available
        ]);
    }

    /**
     * Update location.
     */
    public function updateLocation(Request $request)
    {
        $partner = Auth::guard('delivery_partner')->user();

        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid location data.'
            ]);
        }

        $success = $partner->updateLocation(
            $request->latitude,
            $request->longitude,
            $request->address
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Location updated successfully' : 'Failed to update location'
        ]);
    }

    /**
     * Notify admin about new registration.
     */
    private function notifyAdminNewRegistration(DeliveryPartner $partner): void
    {
        // Implementation for admin notification
        // This could be email, SMS, or in-app notification
    }
}