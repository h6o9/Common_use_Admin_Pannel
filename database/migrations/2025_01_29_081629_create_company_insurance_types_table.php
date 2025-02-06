<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInsuranceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_insurance_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_company_id')->constrained('insurance_companies')->cascadeOnUpdate();
            $table->foreignId('insurance_type_id')->constrained('insurance_types')->cascadeOnUpdate();
            $table->string('price')->nullable();
            $table->string('status')->default(1);
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
        Schema::dropIfExists('company_insurances');
    }
}
