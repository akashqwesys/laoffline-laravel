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
            $table->integer('id');
            $table->string('product_name')->nullable();
            $table->string('catalogue_name')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('model')->nullable();
            $table->date('launch_date')->nullable();
            $table->integer('company')->default('0');
            $table->integer('category')->default('0');
            $table->json('sub_category')->nullable();
            $table->string('main_image')->nullable();
            $table->string('price_list_image')->nullable();
            $table->text('description')->nullable();
            $table->string('complete_flag')->nullable();
            $table->integer('generated_by')->default('0');
            $table->integer('updated_by')->default('0');
            $table->integer('is_delete')->default('0');
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
