<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->integer('id');
            $table->string('company_name')->nullable();
            $table->integer('company_type')->nullable();
            $table->integer('company_country')->nullable();
            $table->integer('company_state')->nullable();
            $table->integer('company_city')->nullable();
            $table->string('company_website')->nullable();
            $table->jsonb('company_landline')->nullable();
            $table->jsonb('company_mobile')->nullable();
            $table->string('company_watchout')->nullable();
            $table->text('company_remark_watchout')->nullable();
            $table->text('company_about')->nullable();
            $table->jsonb('company_category')->nullable();
            $table->integer('company_transport')->default('0');
            $table->integer('company_discount')->nullable();
            $table->integer('company_payment_terms_in_days')->default('0');
            $table->integer('company_opening_balance')->nullable();
            $table->integer('favorite_flag')->default('0');
            $table->integer('is_verified')->default('0');
            $table->integer('verified_by')->default('0');
            $table->integer('generated_by')->default('0');
            $table->integer('updated_by')->default('0');
            $table->integer('is_linked')->default('0');
            $table->integer('is_active')->default('0');
            $table->timestamp('verified_date')->nullable();
            $table->integer('is_delete')->default('0');
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
        Schema::dropIfExists('companies');
    }
}
