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

    // Get the correct image URL (simplified for cloud compatibility)
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://via.placeholder.com/200?text=No+Image';
        }

        $imagePath = $this->image;
        
        // Check if it's already a direct image path (SRM images in images/ folder)
        if (strpos($imagePath, 'images/') === 0) {
            // Remove the duplicate 'images/' prefix and use asset() for public/images/
            $cleanPath = str_replace('images/', '', $imagePath);
            return asset('images/' . $cleanPath);
        }
        
        // For all other cases, use storage path
        return asset('storage/' . $imagePath);
    }
}