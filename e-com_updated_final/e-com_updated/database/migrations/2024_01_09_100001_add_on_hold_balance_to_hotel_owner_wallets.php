<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnHoldBalanceToHotelOwnerWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('hotel_owner_wallets')) {
            return;
        }

        Schema::table('hotel_owner_wallets', function (Blueprint $table) {
            if (!Schema::hasColumn('hotel_owner_wallets', 'on_hold_balance')) {
                $table->decimal('on_hold_balance', 10, 2)->default(0.00);
            }
            if (!Schema::hasColumn('hotel_owner_wallets', 'pending_withdrawals')) {
                $table->decimal('pending_withdrawals', 10, 2)->default(0.00);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('hotel_owner_wallets')) {
            return;
        }
        
        Schema::table('hotel_owner_wallets', function (Blueprint $table) {
            if (Schema::hasColumn('hotel_owner_wallets', 'on_hold_balance')) {
                $table->dropColumn('on_hold_balance');
            }
            if (Schema::hasColumn('hotel_owner_wallets', 'pending_withdrawals')) {
                $table->dropColumn('pending_withdrawals');
            }
        });
    }
}