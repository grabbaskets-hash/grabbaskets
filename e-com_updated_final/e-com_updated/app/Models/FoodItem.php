<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function hotelOwner()
    {
        return $this->belongsTo(HotelOwner::class);
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
}
