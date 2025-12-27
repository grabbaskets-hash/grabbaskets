<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenMinDeliveryProduct extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'subcategory_id',
        'seller_id',
        'description',
        'price',
        'discount',
        'delivery_charge',
        'image',
        'gift_option',
        'stock'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'stock' => 'integer',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

   public function seller()
{
    return $this->belongsTo(\App\Models\User::class, 'seller_id');
}
}