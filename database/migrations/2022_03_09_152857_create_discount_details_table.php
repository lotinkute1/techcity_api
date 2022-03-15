<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('discount_percent');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('discount_id');
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
        Schema::dropIfExists('discount_details');
    }
}
