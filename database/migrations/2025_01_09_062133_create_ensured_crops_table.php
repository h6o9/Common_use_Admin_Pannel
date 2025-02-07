<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Constraint\Constraint;

class CreateEnsuredCropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ensured_crops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('crop_name_id')->constrained('ensured_crop_name')->cascadeOnUpdate();
            $table->string('land_area');
            $table->foreignId('area_unit_id')->constrained('area_units')->cascadeOnUpdate();
            $table->string('insured_amount');
            $table->foreignId('company_id')->constrained('insurance_companies')->cascadeOnUpdate();
            $table->foreignId('insurance_type_id')->constrained('insurance_types')->cascadeOnUpdate();
            $table->foreignId('insurance_subtype_id')->constrained('insurance_sub_types')->cascadeOnUpdate();
            $table->date('insurance_start_date');
            $table->date('insurance_end_date');
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
        Schema::dropIfExists('ensured_crops');
    }
}
