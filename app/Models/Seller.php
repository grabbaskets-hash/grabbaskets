<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Seller extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'email', 'phone', 'billing_address', 'state', 'city', 'pincode', 'password', 'sex', 'gift_option', 'stock',
        'store_name', 'gst_number', 'store_address', 'store_contact'
    ];
}
