<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->integer('id');
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->text('profile_pic')->nullable();
            $table->string('email_id')->nullable();
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();
            $table->integer('user_group')->default('0');
            $table->integer('excel_access')->default('0');
            $table->text('id_proof')->nullable();
            $table->text('ref_full_name')->nullable();
            $table->text('ref_pass_pic')->nullable();
            $table->string('ref_mobile')->nullable();
            $table->text('ref_address')->nullable();
            $table->string('extension_port_id')->nullable();
            $table->string('extension_port_password')->nullable();
            $table->string('web_login')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
