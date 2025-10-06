<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'unique_id',
        'image',
    ];
    // Each category has many subcategories
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
