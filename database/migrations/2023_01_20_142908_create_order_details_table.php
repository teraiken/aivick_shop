<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('order_details', function (Blueprint $table) {
            $table->comment('注文詳細テーブル');

            $table->id();
            $table->foreignId('order_id')->comment('注文ID')->constrained();
            $table->foreignId('product_id')->comment('商品ID')->constrained();
            $table->integer('price')->comment('税抜価格');
            $table->integer('quantity')->comment('個数');
            $table->tinyInteger('tax_rate')->comment('税率');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
};
