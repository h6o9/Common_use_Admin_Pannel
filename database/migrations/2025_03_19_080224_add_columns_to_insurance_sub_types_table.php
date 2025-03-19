<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToInsuranceSubTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_sub_types', function (Blueprint $table) {
            $table->decimal('cost_of_production', 10, 2)->nullable()->after('name');
            $table->decimal('average_yield', 10, 2)->nullable()->after('cost_of_production');
            $table->decimal('historical_average_market_price', 10, 2)->nullable()->after('average_yield');
            $table->decimal('real_time_market_price', 10, 2)->nullable()->after('historical_average_market_price');
            $table->decimal('ensured_yield', 10, 2)->nullable()->after('real_time_market_price');
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
            $table->dropColumn([
                'cost_of_production',
                'average_yield',
                'historical_average_market_price',
                'real_time_market_price',
                'ensured_yield'
            ]);
        });
    }
}
