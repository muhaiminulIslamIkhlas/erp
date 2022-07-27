<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->string('sell_number');
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_phone');
            $table->string('customer_name');
            $table->decimal('discount',10,2)->default(0);
            $table->decimal('other_cost',10,2)->default(0);
            $table->decimal('subtotal',10,2)->default(0);
            $table->decimal('due',10,2)->default(0);
            $table->decimal('payment',10,2)->default(0);
            $table->decimal('qty',10,2)->default(0);
            $table->decimal('total',10,2)->default(0);
            $table->date('date');
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('accounts');
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
        Schema::dropIfExists('sells');
    }
}
