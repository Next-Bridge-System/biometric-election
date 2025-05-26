<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change09InLowerCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lower_courts', function (Blueprint $table) {
            $table->tinyInteger('app_type')->after('app_status');
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
            $table->dropColumn('app_status');
        });
    }
}
