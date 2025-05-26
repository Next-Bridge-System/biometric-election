<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInHighCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('high_courts', function (Blueprint $table) {
            $table->tinyInteger('enr_status_type')->nullable();
            $table->string('enr_status_reason')->nullable();
            $table->string('lc_ledger', 100)->nullable();
            $table->tinyInteger('lc_sdw')->nullable();
            $table->string('lc_last_status')->nullable();
            $table->string('lc_lic', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('high_courts', function (Blueprint $table) {
            $table->dropColumn('enr_status_type');
            $table->dropColumn('enr_status_reason');
            $table->dropColumn('lc_ledger');
            $table->dropColumn('lc_sdw');
            $table->dropColumn('lc_last_status');
            $table->dropColumn('lc_lic');
        });
    }
}
