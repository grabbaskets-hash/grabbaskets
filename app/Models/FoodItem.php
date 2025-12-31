<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_owner_id',
        'name',
        'description',
        'price',
        'discounted_price',
        'category',
        'food_type',
        'images',
        'is_available',
        'is_popular',
        'preparation_time',
        'ingredients',
        'spice_level',
        'allergens',
        'calories',
        'rating',
        'total_orders',
        'sort_order',
        'image', // ✅ REQUIRED

    ];

    protected $casts = [
        'images' => 'array',
        'allergens' => 'array',
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_available' => 'boolean',
        'is_popular' => 'boolean',
    ];

    // Relationships
    // In FoodItem.php
    public function hotelOwner()
    {
        return $this->belongsTo(HotelOwner::class, 'hotel_owner_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id'); // Reuse existing OrderItem
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id'); // Reuse existing Review
    }

    // Helper methods
    public function getFinalPrice()
    {
        return $this->discounted_price ?? $this->price;
    }

    public function getDiscountPercentage()
    {
        if (!$this->discounted_price) {
            return 0;
        }

        return round((($this->price - $this->discounted_price) / $this->price) * 100);
    }

    public function isVegetarian()
    {
        return $this->food_type === 'veg';
    }

    public function isNonVegetarian()
    {
        return $this->food_type === 'non-veg';
    }
    // app/Models/FoodItem.php
    public function getFirstImageUrlAttribute()
    {
        // Check if single image field exists
        if (!empty($this->image)) {
            return $this->getImageUrl($this->image);
        }
        
        // Check images array
        if (!empty($this->images) && is_array($this->images) && !empty($this->images[0])) {
            return $this->getImageUrl($this->images[0]);
        }
        
        // No placeholder - return null if no image
        return null;
    }
    
    /**
     * Get image URL from AWS/R2 or serve-image route
     */
    private function getImageUrl($imagePath)
    {
        if (empty($imagePath)) {
            return 'https://via.placeholder.com/480x300?text=No+Image';
        }
        
        // Normalize slashes
        $imagePath = str_replace('\\', '/', $imagePath);
        
        // If already a full URL, return as is
        if (strpos($imagePath, 'http') === 0) {
            return $imagePath;
        }
        
        // Check if it's a static public image
        if (str_starts_with($imagePath, 'images/')) {
            return asset($imagePath);
        }
        
        // Use serve-image route for AWS/R2 images
        // Determine storage type (r2 for production, public for local)
        $isLaravelCloud = (
            env('LARAVEL_CLOUD_DEPLOYMENT') === true ||
            app()->environment('production') ||
            (isset($_SERVER['SERVER_NAME']) && strpos($_SERVER['SERVER_NAME'], '.laravel.cloud') !== false)
        );
        $type = $isLaravelCloud ? 'r2' : 'public';
        
        return url('/serve-image/' . $type . '/' . ltrim($imagePath, '/'));
    }
    
    /**
     * Get all image URLs
     */
    public function getImageUrlsAttribute()
    {
        $urls = [];
        
        // Add single image if exists
        if (!empty($this->image)) {
            $urls[] = $this->getImageUrl($this->image);
        }
        
        // Add images from array
        if (!empty($this->images) && is_array($this->images)) {
            foreach ($this->images as $img) {
                if (!empty($img)) {
                    $url = $this->getImageUrl($img);
                    if (!in_array($url, $urls)) {
                        $urls[] = $url;
                    }
                }
            }
        }
        
        return $urls; // Return empty array if no images
    }
}
