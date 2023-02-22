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
        Schema::create('orders', function (Blueprint $table) {
            $table->comment('注文テーブル');

            $table->id();
            $table->foreignId('user_id')->comment('会員ID')->constrained();
            $table->string('name')->comment('宛名');
            $table->string('postal_code')->comment('郵便番号');
            $table->tinyInteger('pref_id')->comment('都道府県ID');
            $table->string('address1')->comment('市区町村');
            $table->string('address2')->comment('以降の住所');
            $table->string('phone_number')->comment('電話番号');
            $table->integer('status')->comment('送料');
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
        Schema::dropIfExists('orders');
    }
};
