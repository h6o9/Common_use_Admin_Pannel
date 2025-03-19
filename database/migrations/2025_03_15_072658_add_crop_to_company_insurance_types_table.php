<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCropToCompanyInsuranceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_insurance_types', function (Blueprint $table) {
            // $table->string('crop')->nullable()->after('insurance_type_id');
            // $table->string('district_name')->nullable()->after('crop');
            // $table->string('tehsil')->nullable()->after('district_name');
            // $table->string('benchmark')->nullable()->after('tehsil');
            // $table->string('price_benchmark')->nullable()->after('benchmark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_insurance_types', function (Blueprint $table) {
            //
        });
    }
}
