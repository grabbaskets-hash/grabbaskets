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

    // Get the image URL - Use GitHub CDN for all images
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

        // Use GitHub as CDN for all uploaded images
        // This ensures images are version-controlled and globally accessible
        $githubBaseUrl = "https://raw.githubusercontent.com/grabbaskets-hash/grabbaskets/main/storage/app/public";
        return "{$githubBaseUrl}/{$imagePath}";
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