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
        Schema::table('increment_ids', function (Blueprint $table) {
            // Add unique constraint on financial_year_id to prevent multiple records per year
            $table->unique('financial_year_id');
            // Add index on financial_year_id for faster lookups
            $table->index('financial_year_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('increment_ids', function (Blueprint $table) {
            $table->dropUnique(['financial_year_id']);
            $table->dropIndex(['financial_year_id']);
        });
    }
};
