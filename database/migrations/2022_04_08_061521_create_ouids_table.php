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
        Schema::create('ouids', function (Blueprint $table) {
            $table->id();
            $table->integer('ouid')->default(0);
            $table->integer('financial_year_id')->default(0);
            $table->string('name')->nullable();
            $table->string('inward_type', 100)->nullable();
            $table->string('inward_medium', 100)->nullable();
            $table->string('inward_details')->nullable();
            $table->integer('company_id')->default(0);
            $table->string('company_type', 100)->nullable();
            $table->string('company_person')->nullable();
            $table->integer('generated_by')->default(0);
            $table->integer('assigned_to')->default(0);
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
        Schema::dropIfExists('ouids');
    }
};
