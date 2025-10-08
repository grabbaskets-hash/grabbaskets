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
        Schema::table('users', function (Blueprint $table) {
            // Add missing columns that should be in users table
            $table->string('phone')->unique()->nullable()->after('email');
            $table->string('billing_address')->nullable()->after('phone');
            $table->string('state')->nullable()->after('billing_address');
            $table->string('city')->nullable()->after('state');
            $table->string('pincode')->nullable()->after('city');
            $table->enum('role', ['seller', 'buyer', 'admin'])->default('buyer')->after('pincode');
            $table->enum('sex', ['male', 'female', 'other'])->nullable()->after('role');
            $table->date('dob')->nullable()->after('sex');
            $table->string('profile_picture')->nullable()->after('dob');
            $table->string('default_address')->nullable()->after('profile_picture');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn([
                'phone',
                'billing_address', 
                'state',
                'city',
                'pincode',
                'role',
                'sex',
                'dob',
                'profile_picture',
                'default_address'
            ]);
        });
    }
};
