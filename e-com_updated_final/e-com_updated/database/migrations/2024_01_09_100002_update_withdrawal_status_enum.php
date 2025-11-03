<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateWithdrawalStatusEnumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('hotel_owner_withdrawals')) {
            return;
        }
        
        DB::statement("ALTER TABLE hotel_owner_withdrawals MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('hotel_owner_withdrawals')) {
            return;
        }

        DB::statement("ALTER TABLE hotel_owner_withdrawals MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'pending'");
    }
}