<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add district to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'district')) {
                $table->string('district')->nullable()->after('city');
            }
            if (!Schema::hasColumn('users', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('district');
            }
            if (!Schema::hasColumn('users', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });

        // Add delivery zone fields to products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'delivery_district')) {
                $table->string('delivery_district')->nullable()->after('discount');
            }
            if (!Schema::hasColumn('products', 'available_for_10min')) {
                $table->boolean('available_for_10min')->default(false)->after('delivery_district');
            }
            if (!Schema::hasColumn('products', 'delivery_radius_km')) {
                $table->decimal('delivery_radius_km', 5, 2)->default(5)->after('available_for_10min');
            }
        });

        // Add district to sellers table
        Schema::table('sellers', function (Blueprint $table) {
            if (!Schema::hasColumn('sellers', 'district')) {
                $table->string('district')->nullable()->after('city');
            }
            if (!Schema::hasColumn('sellers', 'store_latitude')) {
                $table->decimal('store_latitude', 10, 8)->nullable()->after('district');
            }
            if (!Schema::hasColumn('sellers', 'store_longitude')) {
                $table->decimal('store_longitude', 11, 8)->nullable()->after('store_latitude');
            }
        });

        // Add delivery type tracking to orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'delivery_zone')) {
                $table->string('delivery_zone')->nullable()->after('delivery_charge');
            }
            if (!Schema::hasColumn('orders', 'is_10min_delivery')) {
                $table->boolean('is_10min_delivery')->default(false)->after('delivery_zone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['district', 'latitude', 'longitude']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['delivery_district', 'available_for_10min', 'delivery_radius_km']);
        });

        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn(['district', 'store_latitude', 'store_longitude']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_zone', 'is_10min_delivery']);
        });
    }
};
