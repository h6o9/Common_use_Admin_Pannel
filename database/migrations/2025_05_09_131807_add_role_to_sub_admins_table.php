<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToSubAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_admins', function (Blueprint $table) {
            
            $table->string('role')->nullable(); // nullable agar required nahi

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_admins', function (Blueprint $table) {
            //
            $table->dropColumn('role');

        });
    }
}
