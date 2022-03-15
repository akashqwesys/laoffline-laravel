<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportMultipleAddressDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_multiple_address_details', function (Blueprint $table) {
            $table->integer('id');
            $table->string('transport_details')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_address')->nullable();
            $table->string('contact_person_office_no')->nullable();
            $table->string('contact_person_email')->nullable();
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
        Schema::dropIfExists('transport_multiple_address_details');
    }
}
