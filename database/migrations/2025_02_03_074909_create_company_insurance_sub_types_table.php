<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInsuranceSubTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_insurance_sub_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_insurance_type_id')->constrained('company_insurance_types')->cascadeOnUpdate();
            $table->foreignId('insurance_subtype_id')->nullable()->constrained('insurance_sub_types')->cascadeOnUpdate();
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
        Schema::dropIfExists('company_insurance_sub_types');
    }
}
