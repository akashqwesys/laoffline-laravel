<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySwotDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_swot_details', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('company_id')->default('0');
            $table->string('strength')->nullable();
            $table->string('weakness')->nullable();
            $table->string('opportunity')->nullable();
            $table->string('threat')->nullable();
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
        Schema::dropIfExists('company_swot_details');
    }
}
