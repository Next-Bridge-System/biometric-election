<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsInLowerCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lower_courts', function (Blueprint $table) {
            $table->boolean('is_excel')->nullable()->default(false);
            $table->string('rf_id', 100)->nullable()->unique();
            $table->char('enr_app_sdw', 4)->nullable();
            $table->text('enr_status_reason')->nullable();
            $table->char('enr_plj_check', 4)->nullable();
            $table->char('enr_gi_check', 4)->nullable();
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
            $table->dropColumn('is_excel');
            $table->dropColumn('rf_id');
            $table->dropColumn('enr_app_sdw');
            $table->dropColumn('enr_status_reason');
            $table->dropColumn('enr_plj_check');
            $table->dropColumn('enr_gi_check');
        });
    }
}
