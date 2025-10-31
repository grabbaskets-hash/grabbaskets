<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPartner;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the delivery partner dashboard.
     */
    public function index()
    {
        try {
            $partner = Auth::guard('delivery_partner')->user();
            
            if (!$partner) {
                return redirect()->route('delivery-partner.login')
                    ->with('error', 'Please login to access the dashboard.');
            }
            
            // Simple dashboard data to avoid complex queries that might fail
            $stats = [
                'total_orders' => 0,
                'completed_orders' => 0,
                'completion_rate' => 0,
                'rating' => $partner->rating ?? 4.5,
                'total_earnings' => 0,
                'this_month_earnings' => 0,
                'today_earnings' => 0,
                'pending_requests' => 0,
                'today_deliveries' => 0,
                'week_deliveries' => 0,
                'month_deliveries' => 0,
                'active_hours' => '0h 0m',
                'wallet_balance' => 0,
                'total_withdrawals' => 0,
                'available_earnings' => 0,
            ];
            
            // Try to get actual data, but fallback to defaults on error
            try {
                $stats = $this->getDashboardStats($partner);
                $recentOrders = $this->getRecentOrders($partner);
                $availableOrders = $this->getAvailableOrders($partner);
                $todayEarnings = $this->getTodayEarnings($partner);
                $notifications = $this->getNotifications($partner);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Dashboard data loading failed, using defaults', [
                    'error' => $e->getMessage(),
                    'partner_id' => $partner->id
                ]);
                $recentOrders = collect([]);
                $availableOrders = collect([]);
                $todayEarnings = 0;
                $notifications = [];
            }

            return view('delivery-partner.dashboard', compact(
                'partner',
                'stats', 
                'recentOrders',
                'availableOrders',
                'todayEarnings',
                'notifications'
            ));
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard loading failed completely', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->view('errors.500', [
                'message' => 'Unable to load dashboard. Please try again later.'
            ], 500);
        }
    }

    /**
     * Get dashboard statistics.
     */
    private function getDashboardStats($partner): array
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // Get wallet information
        $wallet = $partner->wallet;
        
        // Get delivery request statistics
        $totalRequests = \App\Models\DeliveryRequest::where('delivery_partner_id', $partner->id)->count();
        $completedRequests = \App\Models\DeliveryRequest::where('delivery_partner_id', $partner->id)->completed()->count();
        $todayRequests = \App\Models\DeliveryRequest::where('delivery_partner_id', $partner->id)
            ->completed()
            ->whereDate('delivered_at', $today)
            ->count();

        return [
            'total_orders' => $totalRequests,
            'completed_orders' => $completedRequests,
            'completion_rate' => $totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100, 1) : 0,
            'rating' => $partner->rating ?? 4.5,
            'total_earnings' => $wallet ? $wallet->balance : 0,
            'this_month_earnings' => $wallet ? $wallet->this_month_earnings : 0,
            'today_earnings' => $wallet ? $wallet->today_earnings : 0,
            'pending_requests' => \App\Models\DeliveryRequest::where('delivery_partner_id', $partner->id)->active()->count(),
            'today_deliveries' => $todayRequests,
            'week_deliveries' => \App\Models\DeliveryRequest::where('delivery_partner_id', $partner->id)
                ->completed()
                ->where('delivered_at', '>=', $thisWeek)
                ->count(),
            'month_deliveries' => \App\Models\DeliveryRequest::where('delivery_partner_id', $partner->id)
                ->completed()
                ->where('delivered_at', '>=', $thisMonth)
                ->count(),
            'active_hours' => $this->getActiveHours($partner),
            'wallet_balance' => $wallet ? $wallet->balance : 0,
            'total_withdrawals' => $wallet ? $wallet->total_withdrawals : 0,
            'available_earnings' => $wallet ? $wallet->available_earnings : 0,
        ];
    }

    /**
     * Get recent delivery requests for the partner.
     */
    private function getRecentOrders($partner, int $limit = 5)
    {
        return \App\Models\DeliveryRequest::where('delivery_partner_id', $partner->id)
            ->with(['order'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get available delivery requests nearby.
     */
    private function getAvailableOrders($partner, int $limit = 10)
    {
        if (!$partner->is_online || $partner->status !== 'available') {
            return collect();
        }

        // Get orders that are ready for pickup and within delivery radius
        $query = Order::with(['user', 'orderItems.product'])
            ->where('delivery_status', 'pending')
            ->whereNull('delivery_partner_id')
            ->where('order_status', 'confirmed')
            ->orderBy('created_at', 'asc');

        // If partner has location, filter by distance
        if ($partner->current_latitude && $partner->current_longitude) {
            // This is a simplified distance filter
            // In production, you'd use proper spatial queries
            $query->where('delivery_latitude', '!=', null)
                  ->where('delivery_longitude', '!=', null);
        }

        return $query->limit($limit)->get()->filter(function ($order) use ($partner) {
            // Check if partner can deliver to this location
            if ($order->delivery_latitude && $order->delivery_longitude) {
                return $partner->canDeliverTo(
                    $order->delivery_latitude, 
                    $order->delivery_longitude
                );
            }
            return true; // If no coordinates, assume deliverable
        });
    }

    /**
     * Get today's earnings.
     */
    private function getTodayEarnings($partner): float
    {
        return $partner->orders()
            ->where('delivery_status', 'delivered')
            ->whereDate('delivered_at', today())
            ->sum('delivery_fee') ?? 0;
    }

    /**
     * Get notifications for the partner.
     */
    private function getNotifications($partner, int $limit = 5): array
    {
        $notifications = [];

        // Account status notification
        if ($partner->status === 'pending') {
            $notifications[] = [
                'type' => 'warning',
                'title' => 'Account Under Review',
                'message' => 'Your account is being reviewed. You will be notified once approved.',
                'icon' => 'fas fa-clock',
                'time' => $partner->created_at->diffForHumans(),
            ];
        } elseif ($partner->status === 'rejected') {
            $notifications[] = [
                'type' => 'danger',
                'title' => 'Account Rejected',
                'message' => 'Your account has been rejected. Please contact support.',
                'icon' => 'fas fa-times-circle',
                'time' => $partner->updated_at->diffForHumans(),
            ];
        }

        // Document expiry warnings
        if ($partner->license_expiry && Carbon::parse($partner->license_expiry)->diffInDays(today()) <= 30) {
            $notifications[] = [
                'type' => 'warning',
                'title' => 'License Expiry Warning',
                'message' => 'Your license expires on ' . Carbon::parse($partner->license_expiry)->format('d M Y'),
                'icon' => 'fas fa-id-card',
                'time' => 'Important',
            ];
        }

        if ($partner->insurance_expiry && Carbon::parse($partner->insurance_expiry)->diffInDays(today()) <= 30) {
            $notifications[] = [
                'type' => 'warning',
                'title' => 'Insurance Expiry Warning',
                'message' => 'Your insurance expires on ' . Carbon::parse($partner->insurance_expiry)->format('d M Y'),
                'icon' => 'fas fa-shield-alt',
                'time' => 'Important',
            ];
        }

        // Rating notifications
        if ($partner->rating < 3.5 && $partner->total_orders > 10) {
            $notifications[] = [
                'type' => 'info',
                'title' => 'Improve Your Rating',
                'message' => 'Your current rating is ' . $partner->rating . '. Focus on timely deliveries and customer service.',
                'icon' => 'fas fa-star',
                'time' => 'Tip',
            ];
        }

        // New order alerts (simulated)
        if ($partner->isAvailableForDelivery()) {
            $availableCount = $this->getAvailableOrders($partner)->count();
            if ($availableCount > 0) {
                $notifications[] = [
                    'type' => 'success',
                    'title' => 'New Orders Available',
                    'message' => "{$availableCount} orders are available for pickup in your area.",
                    'icon' => 'fas fa-shopping-bag',
                    'time' => 'Now',
                ];
            }
        }

        return array_slice($notifications, 0, $limit);
    }

    /**
     * Get active hours for the partner.
     */
    private function getActiveHours(DeliveryPartner $partner): string
    {
        if (!$partner->last_active_at) {
            return '0h 0m';
        }

        $today = Carbon::today();
        $lastActive = $partner->last_active_at;

        if ($lastActive->isToday()) {
            $hours = $lastActive->diffInHours($today->copy()->endOfDay());
            $minutes = $lastActive->diffInMinutes($today->copy()->endOfDay()) % 60;
            return "{$hours}h {$minutes}m";
        }

        return '0h 0m';
    }

    /**
     * Get quick stats for mobile API.
     */
    public function quickStats(): JsonResponse
    {
        $partner = Auth::guard('delivery_partner')->user();
        $stats = $this->getDashboardStats($partner);

        return response()->json([
            'success' => true,
            'data' => [
                'is_online' => $partner->is_online,
                'is_available' => $partner->is_available,
                'pending_orders' => $stats['pending_orders'],
                'today_earnings' => $stats['today_earnings'],
                'rating' => $stats['rating'],
                'completion_rate' => $stats['completion_rate'],
            ]
        ]);
    }

    /**
     * Search for orders or other data.
     */
    public function search(Request $request): JsonResponse
    {
        $partner = Auth::guard('delivery_partner')->user();
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Search query must be at least 2 characters long.'
            ]);
        }

        // Search in orders
        $orders = $partner->orders()
            ->with(['user', 'orderItems.product'])
            ->where(function ($q) use ($query) {
                $q->where('order_number', 'like', "%{$query}%")
                  ->orWhere('delivery_address', 'like', "%{$query}%")
                  ->orWhereHas('user', function ($userQuery) use ($query) {
                      $userQuery->where('name', 'like', "%{$query}%")
                               ->orWhere('phone', 'like', "%{$query}%");
                  });
            })
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'orders' => $orders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_name' => $order->user->name,
                        'delivery_address' => $order->delivery_address,
                        'total_amount' => $order->total_amount,
                        'delivery_status' => $order->delivery_status,
                        'created_at' => $order->created_at->format('d M Y, h:i A'),
                    ];
                }),
            ]
        ]);
    }

    /**
     * Get notifications via API.
     */
    public function notifications(): JsonResponse
    {
        $partner = Auth::guard('delivery_partner')->user();
        $notifications = $this->getNotifications($partner, 20);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Update partner's working status.
     */
    public function updateStatus(Request $request): JsonResponse
    {
        $partner = Auth::guard('delivery_partner')->user();

        $request->validate([
            'status' => 'required|in:online,offline,available,busy'
        ]);

        $status = $request->status;

        try {
            switch ($status) {
                case 'online':
                    if (!$partner->isAvailableForDelivery()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Your account must be approved to go online.'
                        ]);
                    }
                    $partner->goOnline();
                    break;

                case 'offline':
                    $partner->goOffline();
                    break;

                case 'available':
                    if (!$partner->is_online) {
                        return response()->json([
                            'success' => false,
                            'message' => 'You must be online to become available.'
                        ]);
                    }
                    $partner->update(['is_available' => true]);
                    break;

                case 'busy':
                    $partner->update(['is_available' => false]);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'data' => [
                    'is_online' => $partner->is_online,
                    'is_available' => $partner->is_available,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status. Please try again.'
            ]);
        }
    }
}