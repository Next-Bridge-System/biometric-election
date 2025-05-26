<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change01InGcHighCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gc_high_courts', function (Blueprint $table) {
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
        Schema::table('gc_high_courts', function (Blueprint $table) {
            $table->dropColumn('app_type');
        });
    }
}
