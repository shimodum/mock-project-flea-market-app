<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('condition')->default('new')->change(); // 🔹 デフォルト値を追加
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('condition')->change();
        });
    }
};
