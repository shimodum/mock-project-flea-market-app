<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatedFlagsToTransactions extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // 取引完了後の評価済みフラグ
            $table->boolean('buyer_rated')->default(false)->after('status');
            $table->boolean('seller_rated')->default(false)->after('buyer_rated');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('buyer_rated');
            $table->dropColumn('seller_rated');
        });
    }
}
