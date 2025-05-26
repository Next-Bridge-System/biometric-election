<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UniqueNumberColumnsInApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('reg_no_lc', 100)->nullable()->unique()->change();
            $table->string('sr_no_lc', 100)->nullable()->unique()->change();
            $table->string('sr_no_hc', 100)->nullable()->unique()->change();
            $table->string('license_no_lc', 100)->nullable()->unique()->change();
            $table->string('license_no_hc', 100)->nullable()->unique()->change();
            $table->string('bf_no_lc', 100)->nullable()->unique()->change();
            $table->string('bf_no_hc', 100)->nullable()->unique()->change();
            $table->string('plj_no_lc', 100)->nullable()->unique()->change();
            $table->string('rf_id', 100)->nullable()->unique()->change();
            $table->string('hcr_no', 100)->nullable()->unique()->change();
            $table->string('high_court_roll_no', 100)->nullable()->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique('applications_reg_no_lc_unique');
            $table->dropUnique('applications_sr_no_lc_unique');
            $table->dropUnique('applications_sr_no_hc_unique');
            $table->dropUnique('applications_license_no_lc_unique');
            $table->dropUnique('applications_license_no_hc_unique');
            $table->dropUnique('applications_bf_no_lc_unique');
            $table->dropUnique('applications_bf_no_hc_unique');
            $table->dropUnique('applications_plj_no_lc_unique');
            $table->dropUnique('applications_rf_id_unique');
            $table->dropUnique('applications_hcr_no_unique');
            $table->dropUnique('applications_high_court_roll_no_unique');
        });
    }
}
