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
    if (!empty($this->images) && is_array($this->images) && !empty($this->images[0])) {
        // If it's a local path, convert to public URL
        if (strpos($this->images[0], 'http') !== 0) {
            return Storage::url($this->images[0]);
        }
        return $this->images[0]; // Already a URL
    }
    return 'https://via.placeholder.com/480x300?text=No+Image';
}
}
