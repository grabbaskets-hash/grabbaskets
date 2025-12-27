<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string|exists:users,referral_code',
        ]);

        $user = Auth::user();

        if ($user->referrer_id) {
            return response()->json(['message' => 'You have already redeemed a referral code.'], 400);
        }

        if ($user->referral_code === $request->referral_code) {
            return response()->json(['message' => 'You cannot use your own referral code.'], 400);
        }

        $referrer = User::where('referral_code', $request->referral_code)->first();

        if (!$referrer) {
            return response()->json(['message' => 'Invalid referral code.'], 400);
        }

        DB::transaction(function () use ($user, $referrer) {
            // Update User
            $user->referrer_id = $referrer->id;
            $user->wallet_point += 300;
            $user->save();

            // Optional: You could also reward the referrer here
            // $referrer->wallet_point += 100;
            // $referrer->save();
        });

        return response()->json([
            'message' => 'Referral code applied successfully! 300 points added.',
            'new_balance' => $user->wallet_point
        ]);
    }
}
