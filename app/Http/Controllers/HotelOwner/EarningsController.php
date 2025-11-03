<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EarningsController extends Controller
{
    public function index()
    {
        $hotelOwner = Auth::guard('hotel_owner')->user();

        // Placeholder earnings summary
        $earnings = [
            'today' => 0,
            'week' => 0,
            'month' => $hotelOwner->this_month_revenue ?? 0,
            'total' => $hotelOwner->total_revenue ?? 0,
        ];

        // Simple last 7 days placeholder series
        $earnings_last_7 = [];
        for ($i = 6; $i >= 0; $i--) {
            $earnings_last_7[] = rand(0, 500); // placeholder random values
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
