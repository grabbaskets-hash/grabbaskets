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

    // Get the image URL
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return 'https://via.placeholder.com/200?text=No+Image';
        }

        $imagePath = ltrim($this->image_path, '/');

        // Static public images shipped with app (e.g., images/srm/...)
        if (str_starts_with($imagePath, 'images/')) {
            return '/' . $imagePath;
        }

        // Stored uploads - prioritize local storage in production for reliability
        if (app()->environment('production')) {
            // First try local storage if file exists there
            try {
                if (Storage::disk('public')->exists($imagePath)) {
                    // Use serve route for reliable image serving
                    $pathParts = explode('/', $imagePath, 2);
                    if (count($pathParts) === 2) {
                        return rtrim(config('app.url'), '/') . '/serve-image/' . $pathParts[0] . '/' . $pathParts[1];
                    }
                    // Fallback to standard storage path
                    return rtrim(config('app.url'), '/') . '/storage/' . $imagePath;
                }
            } catch (\Throwable $e) {
                // Continue to R2 attempt
            }
            
            // Fallback to R2 storage
            $r2BaseUrl = config('filesystems.disks.r2.url');
            if (!empty($r2BaseUrl)) {
                return rtrim($r2BaseUrl, '/') . '/' . $imagePath;
            }
            
            // Last resort: app URL + storage path
            return rtrim(config('app.url'), '/') . '/storage/' . $imagePath;
        }

        // Local/dev: serve via storage symlink
        return '/storage/' . $imagePath;
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