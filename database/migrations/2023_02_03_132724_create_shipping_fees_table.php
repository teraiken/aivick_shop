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
        Schema::create('shipping_fees', function (Blueprint $table) {
            $table->comment('送料テーブル');

            $table->id();
            $table->foreignId('area_id')->constrained()->comment('エリアID');
            $table->integer('fee')->comment('送料');
            $table->timestamp('start_date')->comment('適用開始日');
            $table->timestamp('end_date')->nullable()->comment('適用終了日');
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
        Schema::dropIfExists('shipping_fees');
    }
};
