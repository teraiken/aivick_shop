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
        Schema::create('products', function (Blueprint $table) {
            $table->comment('商品テーブル');

            $table->id();
            $table->string('name')->comment('商品名');
            $table->string('image')->comment('画像');
            $table->string('introduction')->comment('説明文');
            $table->integer('price')->comment('税抜価格');
            $table->tinyInteger('status')->comment('ステータス');
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
        Schema::dropIfExists('products');
    }
};
