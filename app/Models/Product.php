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

    // Get the correct image URL (handles both local and R2 storage)
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // First check if it's an R2 URL (if stored in R2)
        try {
            if (\Illuminate\Support\Facades\Storage::disk('r2')->exists($this->image)) {
                $bucket = env('AWS_BUCKET');
                $endpoint = env('AWS_ENDPOINT');
                return "{$endpoint}/{$bucket}/{$this->image}";
            }
        } catch (\Exception $e) {
            // R2 not available, continue to local storage
        }

        // Check local storage paths
        $imagePath = $this->image;
        
        // Try different local storage paths
        if (file_exists(public_path('storage/' . $imagePath))) {
            return asset('storage/' . $imagePath);
        } elseif (file_exists(public_path($imagePath))) {
            return asset($imagePath);
        } elseif (file_exists(public_path('images/' . basename($imagePath)))) {
            return asset('images/' . basename($imagePath));
        } else {
            // Fallback - try the original path anyway
            return asset('storage/' . $imagePath);
        }
    }
}