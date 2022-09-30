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
        Schema::create('samples', function (Blueprint $table) {
            $table->integer('sample_id');
            $table->integer('ouid');
            $table->integer('reference_id');
            $table->integer('inward_id');
            $table->integer('company_id');
            $table->integer('supplier_id');
            $table->integer('reference_via');
            $table->string('sample_via');
            $table->integer('courier_name');
            $table->string('courier_receipt_no');
            $table->datetime('courier_received_time');
            $table->string('weight_of_parcel');
            $table->string('delivery_by');
            $table->integer('product_qty');
            $table->integer('fabric_meters');
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
        Schema::dropIfExists('samples');
    }
};
