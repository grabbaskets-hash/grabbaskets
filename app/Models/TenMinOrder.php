<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenMinOrder extends Model
{
    protected $table = 'ten_min_orders';

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email', // 👈 Add this if you collect email
        'delivery_address',
        'order_total',
        'delivery_fee',
        'total_amount',
        'payment_method', // 👈 Add if you store it
        'status',
        'estimated_delivery_time',
    ];

    protected $casts = [
        'order_total' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'estimated_delivery_time' => 'datetime',
        'user_id' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(TenMinOrderItem::class, 'ten_min_order_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}