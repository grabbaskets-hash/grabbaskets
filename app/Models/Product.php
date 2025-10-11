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

    public function productImages()
    {
        return $this->hasMany(ProductImage::class)->ordered();
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->primary();
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

    // Get the correct image URL (supporting direct URLs, file storage and database storage)
    public function getImageUrlAttribute()
    {
        // Priority 1: Primary image from product_images table
        $primaryImage = $this->primaryImage;
        if ($primaryImage) {
            return $primaryImage->image_url;
        }

        // Priority 2: First image from product_images table
        $firstImage = $this->productImages()->first();
        if ($firstImage) {
            return $firstImage->image_url;
        }

        // Priority 3: Legacy single image field
        if ($this->image) {
            return $this->getLegacyImageUrl();
        }

        // Priority 4: Database stored image
        if ($this->image_data && $this->image_mime_type) {
            return "data:{$this->image_mime_type};base64,{$this->image_data}";
        }
        
        // Priority 5: Fallback placeholder
        return 'https://via.placeholder.com/200?text=No+Image';
    }

    // Helper method for legacy image URL generation
    private function getLegacyImageUrl()
    {
        // Priority 1: Direct external image URL (https://)
        if ($this->image && (str_starts_with($this->image, 'https://') || str_starts_with($this->image, 'http://'))) {
            return $this->image;
        }
        
        // Priority 2: File system image
        if ($this->image) {
            $imagePath = ltrim($this->image, '/');

            // Case A: Static public images shipped with app (e.g., images/srm/...)
            if (str_starts_with($imagePath, 'images/')) {
                // Serve directly from public/images in both envs
                return '/' . $imagePath;
            }

            // Case B: Stored uploads (public disk or R2). In production, always prefer R2 base URL.
            if (app()->environment('production')) {
                $r2BaseUrl = config('filesystems.disks.r2.url');
                if (!empty($r2BaseUrl)) {
                    return rtrim($r2BaseUrl, '/') . '/' . $imagePath;
                }
                // Fallback: default disk URL (if configured)
                try {
                    return Storage::url($imagePath);
                } catch (\Throwable $e) {
                    // Last resort: app URL + storage path
                    return rtrim(config('app.url'), '/') . '/storage/' . $imagePath;
                }
            }

            // Local/dev: serve via storage symlink
            return '/storage/' . $imagePath;
        }

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