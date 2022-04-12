<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInwardActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inward_actions', function (Blueprint $table) {
            $table->id('inward_action_id');
            $table->integer('inward_id')->default(0);
            $table->date('action_date')->nullable();
            $table->integer('employee_id')->default(0);
            $table->text('instruction')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('inward_action');
    }
}
