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
        Schema::create('sale_bills', function (Blueprint $table) {
            $table->id();
            $table->integer('sale_bill_id');
            $table->integer('iuid');
            $table->integer('sale_bill_via');
            $table->string('attachment')->nullable();
            $table->integer('financial_year_id');
            $table->integer('general_ref_id');
            $table->integer('new_or_old_reference');
            $table->string('sale_bill_for')->nullable();
            $table->integer('product_default_category_id');
            $table->jsonb('product_category_id');
            $table->integer('inward_id');
            $table->integer('company_id');
            $table->string('address')->nullable();
            $table->integer('supplier_id');
            $table->integer('agent_id');
            $table->string('supplier_invoice_no')->nullable();
            $table->date('select_date')->nullable();
            $table->integer('change_in_amount');
            $table->string('sign_change')->nullable();
            $table->double('total');
            $table->double('total_peices')->default(0);
            $table->double('total_meters')->default(0);
            $table->text('remark')->nullable();
            $table->tinyInteger('required_followup')->default(0);
            $table->tinyInteger('sale_bill_flag')->default(0);
            $table->tinyInteger('done_outward')->default(0);
            $table->tinyInteger('is_copied')->default(0);
            $table->tinyInteger('is_moved')->default(0);
            $table->integer('inward_main_or_sub_id')->default(0);
            $table->integer('inward_action_id')->default(0);
            $table->tinyInteger('payment_status')->default(0);
            $table->double('received_payment')->default(0);
            $table->double('pending_payment')->default(0);
            $table->tinyInteger('is_deleted')->default(0);
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
        Schema::dropIfExists('sale_bills');
    }
};
