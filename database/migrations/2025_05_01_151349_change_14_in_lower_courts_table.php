<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change14InLowerCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lower_courts', function (Blueprint $table) {
            $table->boolean('intimation_degree_fee')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lower_courts', function (Blueprint $table) {
            $table->dropColumn('intimation_degree_fee');
        });
    }
}
