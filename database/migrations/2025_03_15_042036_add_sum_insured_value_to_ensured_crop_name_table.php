<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSumInsuredValueToEnsuredCropNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ensured_crop_name', function (Blueprint $table) {
            // $table->string('sum_insured_value')->nullable()->after('name');
            // $table->string('harvest_start_time')->nullable()->after('sum_insured_value');
            // $table->string('harvest_end_time')->nullable()->after('harvest_start_time');
            // $table->string('insurance_start_time')->nullable()->after('harvest_end_time');
            // $table->string('insurance_end_time')->nullable()->after('insurance_start_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ensured_crop_name', function (Blueprint $table) {
            $table->dropColumn('sum_insured_value');
            $table->dropColumn('harvest_start_time');
            $table->dropColumn('harvest_end_time');
            $table->dropColumn('insurance_start_time');
            $table->dropColumn('insurance_end_time');
        });
    }
}
