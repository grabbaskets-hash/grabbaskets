<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Carbon\Carbon;

class EarningsController extends Controller
{
    public function index()
    {
        $hotelOwner = Auth::guard('hotel_owner')->user();

        // Real earnings summary using orders table
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $earningsToday = Order::where('seller_id', $hotelOwner->id)
            ->where('status', 'completed')
            ->whereDate('paid_at', $today)
            ->sum('amount');

        $earningsWeek = Order::where('seller_id', $hotelOwner->id)
            ->where('status', 'completed')
            ->whereBetween('paid_at', [$startOfWeek, Carbon::now()])
            ->sum('amount');

        $earningsMonth = Order::where('seller_id', $hotelOwner->id)
            ->where('status', 'completed')
            ->whereBetween('paid_at', [$startOfMonth, Carbon::now()])
            ->sum('amount');

        $earningsTotal = Order::where('seller_id', $hotelOwner->id)
            ->where('status', 'completed')
            ->sum('amount');

        $earnings = [
            'today' => $earningsToday,
            'week' => $earningsWeek,
            'month' => $earningsMonth,
            'total' => $earningsTotal,
        ];

        // Last 7 days: group sums per day
        $earnings_last_7 = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $sum = Order::where('seller_id', $hotelOwner->id)
                ->where('status', 'completed')
                ->whereDate('paid_at', $date)
                ->sum('amount');
            $earnings_last_7[] = (float) $sum;
        }

        return view('hotel-owner.earnings.index', compact('earnings', 'earnings_last_7', 'hotelOwner'));
    }

    public function weekly()
    {
        return redirect()->route('hotel-owner.earnings.index');
    }

    public function monthly()
    {
        return redirect()->route('hotel-owner.earnings.index');
    }

    public function withdraw(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);
        // TODO: Implement withdrawal logic
        return back()->with('success', 'Withdrawal request submitted.');
    }
}
