<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DeliveryPartnerApproved;

class DeliveryPartnerController extends Controller
{
    /**
     * Display a listing of delivery partners.
     */
    public function index()
    {
        $deliveryPartners = DeliveryPartner::latest()
            ->with('wallet')
            ->paginate(20);

        return view('admin.delivery-partners.index', compact('deliveryPartners'));
    }

    /**
     * Show the delivery partner details.
     */
    public function show(DeliveryPartner $deliveryPartner)
    {
        $deliveryPartner->load(['wallet', 'earnings', 'deliveries']);
        return view('admin.delivery-partners.show', compact('deliveryPartner'));
    }

    /**
     * Update the delivery partner status.
     */
    public function updateStatus(Request $request, DeliveryPartner $deliveryPartner)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,suspended'
        ]);

        $oldStatus = $deliveryPartner->status;
        $deliveryPartner->status = $request->status;
        
        if ($oldStatus !== 'approved' && $request->status === 'approved') {
            // Send approval notification
            try {
                $deliveryPartner->notify(new \App\Notifications\DeliveryPartnerApproved());
            } catch (\Exception $e) {
                Log::error('Failed to send approval notification: ' . $e->getMessage());
            }
        }

        $deliveryPartner->save();

        return back()->with('success', 'Delivery partner status updated successfully.');
    }

    /**
     * Show verification documents.
     */
    public function viewDocuments(DeliveryPartner $deliveryPartner)
    {
        return view('admin.delivery-partners.documents', compact('deliveryPartner'));
    }
}