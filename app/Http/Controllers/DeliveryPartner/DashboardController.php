<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPartner;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the delivery partner dashboard.
     */
    public function index(): View
    {
        $partner = Auth::guard('delivery_partner')->user();
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats($partner);
        
        // Get recent orders
        $recentOrders = $this->getRecentOrders($partner);
        
        // Get available orders nearby
        $availableOrders = $this->getAvailableOrders($partner);
        
        // Get today's earnings
        $todayEarnings = $this->getTodayEarnings($partner);
        
        // Get notifications
        $notifications = $this->getNotifications($partner);

        return view('delivery-partner.dashboard.index', compact(
            'partner',
            'stats', 
            'recentOrders',
            'availableOrders',
            'todayEarnings',
            'notifications'
        ));
    }

    /**
     * Get dashboard statistics.
     */
    private function getDashboardStats(DeliveryPartner $partner): array
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_orders' => $partner->total_orders,
            'completed_orders' => $partner->completed_orders,
            'completion_rate' => $partner->completion_rate,
            'rating' => $partner->rating,
            'total_earnings' => $partner->total_earnings,
            'this_month_earnings' => $partner->this_month_earnings,
            'today_earnings' => $partner->today_earnings,
            'pending_orders' => $partner->pending_orders_count,
            'today_deliveries' => $partner->orders()
                ->where('delivery_status', 'delivered')
                ->whereDate('delivered_at', $today)
                ->count(),
            'week_deliveries' => $partner->orders()
                ->where('delivery_status', 'delivered')
                ->where('delivered_at', '>=', $thisWeek)
                ->count(),
            'month_deliveries' => $partner->orders()
                ->where('delivery_status', 'delivered')
                ->where('delivered_at', '>=', $thisMonth)
                ->count(),
            'active_hours' => $this->getActiveHours($partner),
        ];
    }

    /**
     * Get recent orders for the partner.
     */
    private function getRecentOrders(DeliveryPartner $partner, int $limit = 5)
    {
        return $partner->orders()
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get available orders nearby.
     */
    private function getAvailableOrders(DeliveryPartner $partner, int $limit = 10)
    {
        if (!$partner->isAvailableForDelivery()) {
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
    private function getTodayEarnings(DeliveryPartner $partner): float
    {
        return $partner->orders()
            ->where('delivery_status', 'delivered')
            ->whereDate('delivered_at', today())
            ->sum('delivery_fee') ?? 0;
    }

    /**
     * Get notifications for the partner.
     */
    private function getNotifications(DeliveryPartner $partner, int $limit = 5): array
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
        if ($partner->license_expiry && $partner->license_expiry->diffInDays(today()) <= 30) {
            $notifications[] = [
                'type' => 'warning',
                'title' => 'License Expiry Warning',
                'message' => 'Your license expires on ' . $partner->license_expiry->format('d M Y'),
                'icon' => 'fas fa-id-card',
                'time' => 'Important',
            ];
        }

        if ($partner->insurance_expiry && $partner->insurance_expiry->diffInDays(today()) <= 30) {
            $notifications[] = [
                'type' => 'warning',
                'title' => 'Insurance Expiry Warning',
                'message' => 'Your insurance expires on ' . $partner->insurance_expiry->format('d M Y'),
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