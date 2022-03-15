<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnjayCallRecordsIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enjay_call_records_ids', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('reference_id')->default('0');
            $table->string('asteriskhost')->nullable();
            $table->string('event')->nullable();
            $table->string('direction')->nullable();
            $table->string('number')->nullable();
            $table->string('extension')->nullable();
            $table->string('redirectchannel')->nullable();
            $table->string('uniqueid')->nullable();
            $table->timestamp('starttime');
            $table->timestamp('answertime');
            $table->timestamp('endtime');
            $table->integer('duration')->default('0');
            $table->integer('billableseconds')->default('0');
            $table->string('disposition')->nullable();
            $table->text('recordlink')->nullable();
            $table->integer('enjay_flag')->default('0');
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
        Schema::dropIfExists('enjay_call_records_ids');
    }
}
