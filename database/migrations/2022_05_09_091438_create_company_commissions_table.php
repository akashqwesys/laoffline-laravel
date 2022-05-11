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
        Schema::create('company_commissions', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('supplier_id');
            $table->double('commission_percentage');
            $table->integer('flag')->comment('1 = for supplier and 2 = for customer');
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
        Schema::dropIfExists('company_commissions');
    }
};
