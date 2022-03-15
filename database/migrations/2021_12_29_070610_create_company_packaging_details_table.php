<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPackagingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_packaging_details', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('company_id')->default('0');
            $table->string('gst_no')->nullable();
            $table->string('cst_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('vat_no')->nullable();
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
        Schema::dropIfExists('company_packaging_details');
    }
}
