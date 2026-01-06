<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserWithdrawal;
use App\Models\UserWalletTransaction;
use Illuminate\Http\Request;
use Exception;

class UserWithdrawalController extends Controller
{
    /**
     * Display all withdrawal requests for admin
     */
    public function index(Request $request)
    {
        $query = UserWithdrawal::with(['user.bankDetails'])
            ->orderBy('created_at', 'desc');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->paginate(20);

        return view('admin.withdrawals.users.index', compact('withdrawals'));
    }

    /**
     * Approve withdrawal request
     */
    public function approve(Request $request, UserWithdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Only pending withdrawals can be approved.');
        }

        $request->validate([
            'transaction_id' => 'required|string|max:255',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $withdrawal->update([
                'status' => 'completed',
                'transaction_id' => $request->transaction_id,
                'admin_notes' => $request->admin_notes,
                'processed_at' => now(),
            ]);

            return back()->with('success', 'Withdrawal approved and marked as completed.');
        } catch (Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Reject withdrawal request
     */
    public function reject(Request $request, UserWithdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Only pending withdrawals can be rejected.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        try {
            $withdrawal->update([
                'status' => 'rejected',
                'admin_notes' => $request->reason,
                'processed_at' => now(),
            ]);

            // Refund points to user
            $withdrawal->user->addWalletPoints(
                $withdrawal->amount,
                'withdrawal_refund',
                "Refund for rejected withdrawal: {$request->reason}"
            );

            return back()->with('success', 'Withdrawal rejected and points refunded.');
        } catch (Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
