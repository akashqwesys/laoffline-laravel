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
            $table->integer('ouid')->default(0);
            $table->integer('outward_ref_via')->default(0);
            $table->integer('general_output_ref_id')->default(0);
            $table->integer('new_or_old_outward')->default(0);
            $table->text('connected_outward')->nullable();
            $table->date('outward_date')->nullable();
            $table->text('subject')->nullable();
            $table->integer('employee_id')->default(0);
            $table->string('type_of_outward')->nullable();
            $table->string('receiver_number')->nullable();
            $table->string('from_number')->nullable();
            $table->integer('company_id')->default(0);
            $table->integer('supplier_id')->default(0);
            $table->string('courier_name')->nullable();
            $table->string('weight_of_parcel')->nullable();
            $table->string('courier_receipt_no')->nullable();
            $table->dateTime('courier_received_time')->nullable();
            $table->integer('no_of_parcel')->default(0);
            $table->string('from_name')->nullable();
            $table->jsonb('attachments')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('latter_by_id')->default(0);
            $table->string('delivery_by')->nullable();
            $table->string('receiver_email_id')->nullable();
            $table->string('from_email_id')->nullable();
            $table->integer('product_main_id')->default(0);
            $table->jsonb('product_image_id')->nullable();
            $table->integer('outward_link_with_id')->default(0);
            $table->integer('enquiry_complain_for')->default(0);
            $table->text('client_remark')->nullable();
            $table->integer('notify_client')->default(0);
            $table->integer('notify_md')->default(0);
            // $table->integer('required_followup')->default(0);
            $table->integer('courier_agent')->default(0);
            $table->integer('mark_as_draft')->default(0);
            $table->integer('outward_courier_flag')->default(0);
            $table->integer('outward_employee_id')->default(0);
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
        Schema::dropIfExists('outwards');
    }
}
