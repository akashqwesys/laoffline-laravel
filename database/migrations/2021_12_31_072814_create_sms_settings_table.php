<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->integer('id');
            $table->string('employee_id')->nullable();
            $table->string('enquiry_general')->nullable();
            $table->string('enquiry_supplier')->nullable();
            $table->string('enquiry_footer_message')->nullable();
            $table->string('enquiry_followup_general')->nullable();
            $table->string('enquiry_followup_supplier')->nullable();
            $table->string('order_general')->nullable();
            $table->string('order_supplier')->nullable();
            $table->string('order_footer_message')->nullable();
            $table->string('order_followup_general')->nullable();
            $table->string('order_followup_supplier')->nullable();
            $table->string('complain_general')->nullable();
            $table->string('complain_supplier')->nullable();
            $table->string('complain_footer_message')->nullable();
            $table->string('complain_followup_general')->nullable();
            $table->string('complain_followup_supplier')->nullable();
            $table->string('general_general')->nullable();
            $table->string('general_supplier')->nullable();
            $table->string('general_footer_message')->nullable();
            $table->string('general_followup_general')->nullable();
            $table->string('general_followup_supplier')->nullable();
            $table->string('salebill_inward_general')->nullable();
            $table->string('salebill_inward_supplier')->nullable();
            $table->string('salebill_inward_footer_message')->nullable();
            $table->string('salebill_outward_followup_general')->nullable();
            $table->string('salebill_outward_followup_supplier')->nullable();
            $table->string('salebill_outward_followup_footer_message')->nullable();
            $table->string('salebill_followup_general')->nullable();
            $table->string('salebill_followup_supplier')->nullable();
            $table->string('payment_general')->nullable();
            $table->string('payment_supplier')->nullable();
            $table->string('payment_footer_message')->nullable();
            $table->string('payment_outward_followup_general')->nullable();
            $table->string('payment_outward_followup_supplier')->nullable();
            $table->string('payment_outward_footer_message')->nullable();
            $table->string('payment_followup_general')->nullable();
            $table->string('payment_followup_supplier')->nullable();
            $table->string('commission_general')->nullable();
            $table->string('commission_supplier')->nullable();
            $table->string('commission_footer_message')->nullable();
            $table->string('commission_followup_general')->nullable();
            $table->string('commission_followup_supplier')->nullable();
            $table->string('automated_payment_general')->nullable();
            $table->string('automated_payment_supplier')->nullable();
            $table->string('automated_payment_footer_message')->nullable();
            $table->string('automated_commission_followup_general')->nullable();
            $table->string('automated_commission_followup_supplier')->nullable();
            $table->string('automated_commission_followup_footer_message')->nullable();
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
        Schema::dropIfExists('sms_settings');
    }
}
