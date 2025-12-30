<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\HotelOwner;

class FoodOrder extends Model
{
    protected $table = 'food_orders';

    protected $fillable = [
        'hotel_owner_id',
        'shop_name',            // 👈 ADD
        'shop_address',         // 👈 ADD (critical for delivery partner!)
        'customer_name',
        'customer_phone',
        'customer_email',       // 👈 Optional but useful
        'delivery_address',
        'food_total',
        'delivery_fee',
        'total_amount',
        'payment_method',
        'status',
        'payment_reference',
        'wallet_discount',
        'estimated_delivery_time',
        'delivery_partner_id'   // 👈 Optional: assign delivery person
    ];

    protected $casts = [
        'estimated_delivery_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(FoodOrderItem::class, 'food_order_id');
    }

    public function hotelOwner()
    {
        return $this->belongsTo(HotelOwner::class, 'hotel_owner_id'); // ✅ Fixed
    }

    // Optional: if you add delivery_partner_id
    // public function deliveryPartner()
    // {
    //     return $this->belongsTo(User::class, 'delivery_partner_id');
    // }

    public function getSubtotalAttribute()
    {
        return $this->food_total;
    }

    public function getDiscountAmountAttribute()
    {
        // If you add a discount column later, use it here.
        // For now, return 0 or calculate if logic exists.
        return 0.00; 
    }
}