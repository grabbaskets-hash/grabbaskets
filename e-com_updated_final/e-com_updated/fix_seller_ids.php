<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// Usage: php artisan tinker < fix_seller_ids.php

// Set these variables:
$sellerEmail = 'SELLER_EMAIL_HERE'; // Change to the correct seller's email

$user = DB::table('users')->where('email', $sellerEmail)->first();
if (!$user) {
    echo "Seller not found.\n";
    return;
}

$sellerId = $user->id;

// Find all products that should belong to this seller (e.g., by unique_id prefix, or update all)
// Example: update all products with a specific seller_id (change 0 to the wrong seller_id if needed)
$affected = DB::table('products')
    ->where('seller_id', 0) // Change 0 to the wrong seller_id if needed
    ->update(['seller_id' => $sellerId]);

echo "Updated $affected products to seller_id $sellerId for $sellerEmail.\n";
