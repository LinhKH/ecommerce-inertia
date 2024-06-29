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
            $table->string('product_name');
            $table->string('category');
            $table->string('brand')->nullable();
            $table->string('unit');
            $table->integer('min_qty');
            $table->string('tags')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('refundable')->default(0);
            $table->string('gallery_img')->nullable();
            $table->string('thumbnail_img')->nullable();
            $table->string('colors')->nullable();
            $table->string('attributes')->nullable();
            $table->integer('unit_price');
            $table->integer('taxable_price');
            $table->integer('tax');
            $table->integer('quantity');
            $table->string('date_range')->nullable();
            $table->string('external_link')->nullable();
            $table->string('external_button')->nullable();
            $table->text('description');
            $table->string('meta_title');
            $table->string('meta_desc')->nullable();
            $table->string('slug');
            $table->string('video_provider')->nullable();
            $table->string('video_link')->nullable();
            $table->boolean('show_quantity')->default(1);
            $table->boolean('today_deal')->default('0');
            $table->integer('discount')->nullable();
            $table->string('discount_type')->nullable();
            $table->string('shipping_charges');
            $table->string('shipping_days');
            $table->boolean('status')->default('1');
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
