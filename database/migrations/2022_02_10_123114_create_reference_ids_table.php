<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferenceIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_ids', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('reference_id')->default('0');
            $table->integer('employee_id')->default('0');
            $table->integer('financial_year_id')->default('0');
            $table->integer('inward_or_outward')->default('0');
            $table->string('type_of_inward', 32)->nullable();
            $table->integer('company_id')->default('0');
            $table->date('selection_date')->nullable();
            $table->string('from_name')->nullable();
            $table->string('from_number', 32)->nullable();
            $table->string('receiver_number', 32)->nullable();
            $table->string('from_email_id')->nullable();
            $table->string('receiver_email_id')->nullable();
            $table->integer('latter_by_id')->default('0');
            $table->string('courier_name')->nullable();
            $table->string('weight_of_parcel')->nullable();
            $table->string('courier_receipt_no')->nullable();
            $table->date('courier_received_time')->nullable();
            $table->string('delivery_by')->nullable();
            $table->integer('mark_as_sample')->default('0');
            $table->integer('gmail_mail_id')->nullable()->default('0');
            $table->string('gmail_folder_name',100)->nullable();
            $table->integer('is_deleted')->default('0');
            $table->dateTime('date_added')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('reference_ids');
    }
}
