<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenMinDeliveryProduct extends Model
{
    protected $fillable = [
        'product_id',
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

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }
}
