<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // 👈 Add this import

class Product extends Model
{
    protected $fillable = [
        'name',
        'unique_id',
        'category_id',
        'subcategory_id',
        'seller_id',
        'image',
        'description',
        'price',
        'discount',
        'delivery_charge',
        'gift_option',
        'stock',
    ];

    // ✅ Add this relationship
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function isWishlistedBy($user)
    {
        if (!$user) return false;
        return $this->wishlists()->where('user_id', $user->id)->exists();
    }

    // Get the final selling price after discount
    public function getFinalPriceAttribute()
    {
        if ($this->discount > 0) {
            return $this->price * (1 - $this->discount / 100);
        }
        return $this->price;
    }

    // Get the savings amount
    public function getSavingsAttribute()
    {
        if ($this->discount > 0) {
            return $this->price - $this->final_price;
        }
        return 0;
    }
}