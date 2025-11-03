<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddIndicesHotelOwnerFinancialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_owner_wallets', function (Blueprint $table) {
            $table->index(['hotel_owner_id', 'balance', 'on_hold_balance'], 'idx_wallet_owner_balance');
        });

        Schema::table('hotel_owner_withdrawals', function (Blueprint $table) {
            $table->index(['hotel_owner_wallet_id', 'status', 'requested_at'], 'idx_withdrawal_wallet_status');
            $table->index(['status', 'processed_at'], 'idx_withdrawal_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_owner_withdrawals', function (Blueprint $table) {
            $table->dropIndex('idx_withdrawal_wallet_status');
            $table->dropIndex('idx_withdrawal_status');
        });

        Schema::table('hotel_owner_wallets', function (Blueprint $table) {
            $table->dropIndex('idx_wallet_owner_balance');
        });
    }
}