<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'original_name',
        'mime_type',
        'file_size',
        'sort_order',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'file_size' => 'integer',
        'sort_order' => 'integer'
    ];

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Get the image URL - Use GitHub CDN in production, serve-image in development
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        $imagePath = ltrim($this->image_path, '/');

        // Static public images shipped with app (e.g., images/srm/...)
        if (str_starts_with($imagePath, 'images/')) {
            return asset($imagePath);
        }

        // Production Laravel Cloud: Use R2 direct public URL
        // Check if actually running on Laravel Cloud (not just APP_ENV=production locally)
        $isLaravelCloud = $this->isLaravelCloud();
        
        if ($isLaravelCloud) {
            // Return R2 public URL directly
            $r2PublicUrl = config('filesystems.disks.r2.url', env('AWS_URL'));
            return "{$r2PublicUrl}/{$imagePath}";
        }

        // Local (even if APP_ENV=production): Use serve-image route
        $parts = explode('/', $imagePath, 2);
        if (count($parts) === 2) {
            return url('/serve-image/' . $parts[0] . '/' . $parts[1]);
        }
        return url('/serve-image/products/' . $imagePath);
    }

    /**
     * Check if running on Laravel Cloud
     */
    private function isLaravelCloud()
    {
        // Explicit flag takes precedence
        if (env('LARAVEL_CLOUD_DEPLOYMENT') === true) {
            return true;
        }

        // Check if running on Laravel Cloud based on server name
        if (app()->environment('production') && 
            isset($_SERVER['SERVER_NAME']) && 
            str_contains($_SERVER['SERVER_NAME'], '.laravel.cloud')) {
            return true;
        }

        // Check for Laravel Vapor environment
        if (env('VAPOR_ENVIRONMENT') !== null) {
            return true;
        }

        return false;
    }

    // Get the original, direct image URL (GitHub CDN)
    public function getOriginalUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        $imagePath = ltrim($this->image_path, '/');

        // Static public images
        if (str_starts_with($imagePath, 'images/')) {
            return '/' . $imagePath;
        }

        // Use GitHub as CDN for original images too
        $githubBaseUrl = "https://raw.githubusercontent.com/grabbaskets-hash/grabbaskets/main/storage/app/public";
        return "{$githubBaseUrl}/{$imagePath}";
    }

    // Get formatted file size
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return null;
        
        if ($this->file_size < 1024) {
            return $this->file_size . ' B';
        } elseif ($this->file_size < 1048576) {
            return round($this->file_size / 1024, 2) . ' KB';
        } else {
            return round($this->file_size / 1048576, 2) . ' MB';
        }
    }

    // Scope for primary images
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    // Scope for ordered images
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }
}