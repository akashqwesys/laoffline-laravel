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
        Schema::create('inward_samples', function (Blueprint $table) {
            $table->id('inward_sample_id');
            $table->integer('inward_id')->default(0);
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->double('price')->default(0);
            $table->integer('qty')->default(0);
            $table->double('meters')->default(0);
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
        Schema::dropIfExists('inward_samples');
    }
};
