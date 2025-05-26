<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesInLowerCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lower_courts', function (Blueprint $table) {
            $table->string('cnic_no', 100)->nullable()->unique()->change();
            $table->string('reg_no_lc', 100)->nullable()->unique()->change();
            $table->string('sr_no_lc', 100)->nullable()->unique()->change();
            $table->string('license_no_lc', 100)->nullable()->unique()->change();
            $table->string('bf_no_lc', 100)->nullable()->unique()->change();
            $table->string('gi_no_lc', 100)->nullable()->unique()->change();
            $table->string('plj_no_lc', 100)->nullable()->unique()->change();
            $table->string('rcpt_no_lc', 100)->nullable()->unique()->change();
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
            $table->dropUnique('lower_courts_cnic_no_unique');
            $table->dropUnique('lower_courts_reg_no_lc_unique');
            $table->dropUnique('lower_courts_sr_no_lc_unique');
            $table->dropUnique('lower_courts_license_no_lc_unique');
            $table->dropUnique('lower_courts_bf_no_lc_unique');
            $table->dropUnique('lower_courts_gi_no_lc_unique');
            $table->dropUnique('lower_courts_plj_no_lc_unique');
            $table->dropUnique('lower_courts_rcpt_no_lc_unique');
        });
    }
}
