<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsReturnFollowupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_return_followups', function (Blueprint $table) {
            $table->id('goods_return_followup_id');
            $table->integer('payment_id');
            $table->integer('reference_id');
            $table->integer('reference_via');
            $table->integer('new_or_old_reference');
            $table->integer('company_id');
            $table->integer('supplier_id');
            $table->integer('inward_or_outward');
            $table->string('inward_via')->nullable();
            $table->string('attachment');
            $table->integer('followup_by');
            $table->string('inform_md',32)->nullable();
            $table->string('followup_via',32)->nullable();
            $table->date('next_followup_date')->nullable();
            $table->time('followup_time')->nullable();
            $table->integer('is_completed');
            $table->string('remark')->nullable();
            $table->integer('assign_to');
            $table->string('inward_via_name')->nullable();
            $table->integer('courier_name');
            $table->integer('l_r_no');
            $table->date('l_r_date')->nullable();
            $table->integer('courier_no');
            $table->date('courier_date')->nullable();
            $table->integer('parcel_amount');
            $table->string('parcel_weight')->nullable();
            $table->integer('freight_charge');
            $table->integer('no_of_parcel');
            $table->date('received_date')->nullable();
            $table->string('to_pay_or_paid',32)->nullable();
            $table->string('receiver_name')->nullable();
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
        Schema::dropIfExists('goods_return_followup');
    }
}
