<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutwardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outwards', function (Blueprint $table) {
            $table->id('outward_id');
            $table->integer('ouid');
            $table->integer('outward_ref_via');
            $table->integer('general_output_ref_id');
            $table->integer('new_or_old_outward');
            $table->integer('connected_outward')->nullable();
            $table->date('outward_date')->nullable();
            $table->text('subject')->nullable();
            $table->integer('employe_id');
            $table->string('type_of_outward')->nullable();
            $table->string('from_number')->nullable();
            $table->integer('company_id');
            $table->integer('supplier_id');
            $table->string('courier_name')->nullable();
            $table->string('weight_of_parcel')->nullable();
            $table->string('courier_receipt_no')->nullable();
            $table->dateTime('courier_received_time')->nullable();
            $table->integer('no_of_parcel');
            $table->string('from_name')->nullable();
            $table->text('attachments')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('latter_by_id');
            $table->string('delivery_by')->nullable();
            $table->string('receiver_email_id')->nullable();
            $table->string('from_email_id')->nullable();
            $table->integer('product_main_id');
            $table->text('product_image_id')->nullable();
            $table->integer('outward_link_with_id');
            $table->integer('enquiry_complain_for');
            $table->text('client_remark')->nullable();
            $table->integer('notify_client');
            $table->integer('notify_md');
            $table->integer('required_followup');
            $table->integer('courier_agent');
            $table->integer('mark_as_draft');
            $table->integer('outward_courier_flag');
            $table->integer('outward_employe_id');
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
        Schema::dropIfExists('outward');
    }
}
