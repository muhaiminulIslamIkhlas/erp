<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->string('product_name');
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->decimal('purchase_price',10,2);
            $table->decimal('selling_price',10,2);
            $table->decimal('initial_stock',10,2)->default(0);
            $table->string('warrenty')->nullable();
            $table->string('guarantee')->nullable();
            $table->string('description')->nullable();
            $table->boolean('available_for_online')->default(0);
            $table->string('online_image')->nullable();
            $table->string('online_image_second')->nullable();
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
}
