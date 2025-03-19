<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistrictNameToInsuranceSubTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_sub_types', function (Blueprint $table) {
            // $table->string('district_name')->nullable()->nullable()->after('name');
            // $table->string('tehsil')->nullable()->after('district_name');
            // $table->string('current_yield')->nullable()->after('tehsil');
            // $table->string('year')->nullable()->after('current_yield');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insurance_sub_types', function (Blueprint $table) {
            $table->dropColumn('district_name');
            $table->dropColumn('tehsil');
            $table->dropColumn('current_yield');
            $table->dropColumn('year');
        });
    }
}
