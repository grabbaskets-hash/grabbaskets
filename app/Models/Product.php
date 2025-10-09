<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
        'image_data',
        'image_mime_type',
        'image_size',
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

    // Get the correct image URL (supporting both file storage and database storage)
    public function getImageUrlAttribute()
    {
        // Priority 1: Database stored image
        if ($this->image_data && $this->image_mime_type) {
            return "data:{$this->image_mime_type};base64,{$this->image_data}";
        }
        
        // Priority 2: File system image
        if ($this->image) {
            $imagePath = $this->image;
            
            // Check if it's already a direct image path (SRM images in images/ folder)
            if (strpos($imagePath, 'images/') === 0) {
                // Remove the duplicate 'images/' prefix
                $cleanPath = str_replace('images/', '', $imagePath);

                // For cloud deployment, first try to serve from the configured storage disk
                if (app()->environment('production')) {
                    // If the file exists on the default disk, return that URL
                    if (Storage::exists('images/' . $cleanPath)) {
                        return Storage::url('images/' . $cleanPath);
                    }

                    // Otherwise fall back to the app url + /images path (useful for static deploys)
                    return rtrim(config('app.url'), '/') . '/images/' . $cleanPath;
                }

                // For local development, use relative path
                return '/images/' . $cleanPath;
            }
            
            // Check if it's a cloud storage path (products/ folder for new uploads)
            if (strpos($imagePath, 'products/') === 0) {
                // For production, use Storage::url() to get cloud URL
                if (app()->environment('production')) {
                    return Storage::url($imagePath);
                }
                
                // For local development, fallback to storage path
                return '/storage/' . $imagePath;
            }
            
            // For all other cases, use storage path
            if (app()->environment('production')) {
                // Use cloud storage URL for production
                return Storage::url($imagePath);
            }
            
            return '/storage/' . $imagePath;
        }
        
        // Priority 3: Fallback placeholder
        return 'https://via.placeholder.com/200?text=No+Image';
    }

    // Store image in database
    public function storeImageInDatabase($imageFile)
    {
        if (!$imageFile || !$imageFile->isValid()) {
            return false;
        }

        try {
            $imageData = base64_encode(file_get_contents($imageFile->getPathname()));
            $mimeType = $imageFile->getMimeType();
            $size = $imageFile->getSize();

            $this->update([
                'image_data' => $imageData,
                'image_mime_type' => $mimeType,
                'image_size' => $size,
                'image' => null // Clear file path since we're using database storage
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to store image in database: ' . $e->getMessage());
            return false;
        }
    }

    // Get image size in human readable format
    public function getImageSizeFormattedAttribute()
    {
        if (!$this->image_size) return null;
        
        if ($this->image_size < 1024) {
            return $this->image_size . ' B';
        } elseif ($this->image_size < 1048576) {
            return round($this->image_size / 1024, 2) . ' KB';
        } else {
            return round($this->image_size / 1048576, 2) . ' MB';
        }
    }
}