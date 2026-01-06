<?php

namespace App\Http\Controllers;

use App\Models\UserBankDetail;
use App\Models\UserWithdrawal;
use App\Models\UserWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class WithdrawalController extends Controller
{
    /**
     * Display withdrawal dashboard for user
     */
    public function index()
    {
        $user = Auth::user();
        $withdrawals = $user->withdrawals;
        $bankDetails = $user->bankDetails;

        return view('profile.withdrawals.index', compact('user', 'withdrawals', 'bankDetails'));
    }

    /**
     * Store bank details for the user
     */
    public function saveBankDetails(Request $request)
    {
        $request->validate([
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code' => 'required|string|max:20',
            'bank_name' => 'required|string|max:100',
        ]);

        $user = Auth::user();

        UserBankDetail::updateOrCreate(
            ['user_id' => $user->id],
            $request->all()
        );

        return back()->with('success', 'Bank details saved successfully.');
    }

    /**
     * Process withdrawal request
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->canWithdraw()) {
            return back()->with('error', 'You need more than 3 successful referrals to withdraw.');
        }

        if (!$user->bankDetails) {
            return back()->with('error', 'Please save your bank details first.');
        }

        $request->validate([
            'amount' => 'required|integer|min:300|max:' . $user->wallet_point,
        ]);

        try {
            // Create withdrawal request
            UserWithdrawal::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'status' => 'pending',
            ]);

            // Deduct points immediately to lock them
            $user->addWalletPoints(
                -$request->amount,
                'withdrawal_request',
                "Withdrawal request of {$request->amount} points"
            );

            return back()->with('success', 'Withdrawal request submitted successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
