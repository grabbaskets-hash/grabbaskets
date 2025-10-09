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

        $imagePath = $this->image;
        
        // Check if it's already a direct image path (SRM images in images/ folder)
        if (strpos($imagePath, 'images/') === 0) {
            return asset($imagePath);
        }
        
        // In cloud environment, try R2 first (safer approach)
        try {
            $bucket = env('AWS_BUCKET');
            $endpoint = env('AWS_ENDPOINT');
            
            // If we have R2 configuration, assume R2 storage for cloud environment
            if ($bucket && $endpoint) {
                return "{$endpoint}/{$bucket}/{$imagePath}";
            }
        } catch (\Exception $e) {
            // R2 not configured, continue to local storage
        }

        // Fallback to local storage paths (safer file existence checking)
        try {
            // Try common storage paths without file_exists() to avoid cloud filesystem issues
            $storagePaths = [
                'storage/' . $imagePath,
                $imagePath,
                'images/' . basename($imagePath)
            ];
            
            // Return the first logical path (in cloud, we assume storage/ is the standard)
            return asset('storage/' . $imagePath);
            
        } catch (\Exception $e) {
            // Final fallback
            return asset('storage/' . $imagePath);
        }
    }
}