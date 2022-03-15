<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInwardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inwards', function (Blueprint $table) {
            $table->id('inward_id');
            $table->integer('iuid');
            $table->string('call_by', 100)->nullable();
            $table->integer('inward_ref_via');
            $table->integer('general_input_ref_id');
            $table->integer('new_or_old_inward');
            $table->integer('financial_year_id');
            $table->integer('connected_inward');
            $table->date('inward_date')->nullable();
            $table->text('subject')->nullable();
            $table->integer('employe_id');
            $table->string('type_of_inward')->nullable();
            $table->string('receiver_number')->nullable();
            $table->integer('company_id');
            $table->integer('supplier_id');
            $table->string('courier_name')->nullable();
            $table->string('weight_of_parcel')->nullable();
            $table->string('courier_receipt_no')->nullable();
            $table->dateTime('courier_received_time')->nullable();
            $table->string('from_name')->nullable();
            $table->text('attachments')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('latter_by_id');
            $table->string('delivery_by')->nullable();
            $table->string('receiver_email_id')->nullable();
            $table->string('from_email_id')->nullable();
            $table->integer('product_main_id');
            $table->text('product_image_id')->nullable();
            $table->integer('inward_link_with_id');
            $table->integer('enquiry_complain_for');
            $table->text('client_remark')->nullable();
            $table->integer('notify_client');
            $table->integer('notify_md');
            $table->integer('required_followup');
            $table->string('delivery_period')->nullable();
            $table->string('to_name')->nullable();
            $table->integer('mark_as_draft');
            $table->string('sample_via',32)->nullable();
            $table->integer('sample_for');
            $table->text('sample_prod_or_fabric')->nullable();
            $table->integer('product_qty');
            $table->integer('fabric_meters');
            $table->tinyInteger('is_deleted');
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
        Schema::dropIfExists('inward');
    }
}
