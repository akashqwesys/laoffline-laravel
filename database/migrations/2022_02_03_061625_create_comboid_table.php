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
            $table->id();
            $table->integer('comboid');
            $table->integer('iuid')->nullable();
            $table->integer('ouid')->nullable();
            $table->integer('general_ref_id')->nullable();
            $table->string('follow_as_inward_or_outward',32)->nullable();
            $table->integer('system_module_id')->nullable();
            $table->integer('main_or_followup')->nullable();
            $table->integer('generated_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('inward_or_outward_flag')->nullable();
            $table->integer('inward_or_outward_id')->nullable();
            $table->integer('sale_bill_id')->nullable();
            $table->integer('payment_id')->nullable();
            $table->integer('payment_followup_id')->nullable();
            $table->integer('goods_return_id')->nullable();
            $table->integer('good_return_followup_id')->nullable();
            $table->integer('commission_id')->nullable();
            $table->integer('commission_followup_id')->nullable();
            $table->integer('commission_invoice_id')->nullable();
            $table->tinyInteger('is_invoice')->nullable();
            $table->integer('sample_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('inward_ref_via')->nullable();
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
            $table->integer('new_or_old_inward_or_outward')->nullable();
            $table->text('subject')->nullable();
            $table->text('attachments')->nullable();
            $table->text('outward_attachments')->nullable();
            $table->integer('outward_employe_id')->nullable();
            $table->integer('default_category_id')->nullable();
            $table->integer('main_category_id')->nullable();
            $table->integer('agent_id')->nullable();
            $table->string('supplier_invoice_no',32)->nullable();
            $table->integer('total')->nullable();
            $table->integer('sale_bill_flag')->nullable();
            $table->string('receipt_mode',32)->nullable();
            $table->integer('receipt_amount')->nullable();
            $table->integer('tds')->nullable();
            $table->integer('net_received_amount')->nullable();
            $table->integer('received_commission_amount')->nullable();
            $table->date('action_date')->nullable();
            $table->string('action_instruction')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->time('next_follow_up_time')->nullable();
            $table->integer('assigned_to')->nullable();
            $table->text('remark')->nullable();
            $table->text('being_late')->nullable();
            $table->integer('financial_year_id')->nullable();
            $table->text('system_url')->nullable();
            $table->integer('required_followup')->nullable();
            $table->string('enjay_uniqueid')->nullable();
            $table->integer('is_completed')->default('0');
            $table->integer('mark_as_draft')->default('0');
            $table->integer('color_flag_id')->default('0');
            $table->integer('product_qty')->default('0');
            $table->integer('fabric_meters')->default('0');
            $table->integer('sample_return_qty')->default('0');
            $table->tinyInteger('mobile_flag')->default('0');
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
        Schema::dropIfExists('comboid');
    }
}
