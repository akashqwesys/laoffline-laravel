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
        Schema::create('references', function (Blueprint $table) {
            $table->id();
            $table->integer('reference_id');
            $table->integer('employee_id');
            $table->integer('financial_year_id');
            $table->integer('inward_or_outward');
            $table->string('type_of_inward');
            $table->integer('company_id');
            $table->date('selection_date');
            $table->string('from_name')->nullable();
            $table->string('from_number')->nullable();
            $table->string('receiver_number')->nullable();
            $table->string('from_email_id')->nullable();
            $table->string('receiver_email_id')->nullable();
            $table->string('courier_name')->nullable();
            $table->integer('latter_by_id');
            $table->string('weight_of_parcel')->nullable();
            $table->string('courier_receipt_no')->nullable();
            $table->dateTime('courier_received_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('delivery_by')->nullable();
            $table->integer('mark_as_sample');
            $table->integer('gmail_mail_id');
            $table->string('gmail_folder_name')->nullable();
            $table->string('enjay_uniqueid')->nullable();
            $table->integer('is_deleted')->default('0');
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
        Schema::dropIfExists('references');
    }
};
