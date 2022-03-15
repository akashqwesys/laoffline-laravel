<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComboidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comboids', function (Blueprint $table) {
            $table->id('comboid');
            $table->integer('iuid');
            $table->integer('ouid');
            $table->integer('general_ref_id');
            $table->string('follow_as_inward_or_outward',32)->nullable();
            $table->integer('system_module_id');
            $table->integer('main_or_followup');
            $table->integer('generated_by');
            $table->integer('updated_by');
            $table->integer('inward_or_outward_flag');
            $table->integer('inward_or_outward_id');
            $table->integer('sale_bill_id');
            $table->integer('payment_id');
            $table->integer('payment_followup_id');
            $table->integer('goods_return_id');
            $table->integer('good_return_followup_id');
            $table->integer('commission_id');
            $table->integer('commission_followup_id');
            $table->integer('commission_invoice_id');
            $table->tinyInteger('is_invoice');
            $table->integer('sample_id');
            $table->integer('company_id');
            $table->integer('supplier_id');
            $table->integer('inward_ref_via');
            $table->string('company_type',100)->nullable();
            $table->string('inform_md',32)->nullable();
            $table->string('followup_via',32)->nullable();
            $table->string('inward_or_outward_via',32)->nullable();
            $table->date('selection_date')->nullable();
            $table->string('from_name')->nullable();
            $table->string('from_number',32)->nullable();
            $table->string('receiver_number',32)->nullable();
            $table->string('from_email_id')->nullable();
            $table->string('receiver_email_id')->nullable();
            $table->integer('new_or_old_inward_or_outward');
            $table->text('subject')->nullable();
            $table->text('attachments')->nullable();
            $table->text('outward_attachments')->nullable();
            $table->integer('outward_employe_id');
            $table->integer('default_category_id');
            $table->integer('main_category_id');
            $table->integer('agent_id');
            $table->string('supplier_invoice_no',32);
            $table->integer('total');
            $table->integer('sale_bill_flag');
            $table->string('receipt_mode',32)->nullable();
            $table->integer('receipt_amount');
            $table->integer('tds');
            $table->integer('net_received_amount');
            $table->integer('received_commission_amount');
            $table->date('action_date')->nullable();
            $table->string('action_instruction')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->time('next_follow_up_time')->nullable();
            $table->integer('assigned_to');
            $table->text('remark')->nullable();
            $table->text('being_late')->nullable();
            $table->integer('financial_year_id');
            $table->text('system_url')->nullable();
            $table->integer('required_followup');
            $table->string('enjay_uniqueid')->nullable();
            $table->integer('is_completed');
            $table->integer('mark_as_draft');
            $table->integer('color_flag_id');
            $table->integer('product_qty');
            $table->integer('fabric_meters');
            $table->integer('sample_return_qty');
            $table->tinyInteger('mobile_flag');
            $table->integer('is_deleted');
            $table->dateTime('date_added');
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
        Schema::dropIfExists('comboid');
    }
}
