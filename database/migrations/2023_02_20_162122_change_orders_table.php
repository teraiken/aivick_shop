<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('pref_id')->comment('都道府県ID')->change();
            $table->foreign('pref_id')->references('id')->on('prefs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_pref_id_foreign');
            $table->dropIndex('orders_pref_id_foreign');
        });
        Schema::table('orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE orders CHANGE pref_id pref_id TINYINT NOT NULL");
        });
    }
};
