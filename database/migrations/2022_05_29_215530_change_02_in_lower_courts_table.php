<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change02InLowerCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lower_courts', function (Blueprint $table) {
            $table->string('is_engaged_in_business', 10)->nullable()->after('final_submitted_at'); // # 12
            $table->string('is_practice_in_pbc', 10)->nullable()->after('final_submitted_at'); // # 13
            $table->string('practice_place', 100)->nullable()->after('final_submitted_at'); // # 13
            $table->string('is_declared_insolvent', 10)->nullable()->after('final_submitted_at'); // # 14
            $table->string('is_dismissed_from_gov', 10)->nullable()->after('final_submitted_at'); // # 15
            $table->string('dismissed_reason', 100)->nullable()->after('final_submitted_at'); // # 15
            $table->string('is_enrolled_as_adv', 10)->nullable()->after('final_submitted_at'); // # 16
            $table->string('is_offensed', 10)->nullable()->after('final_submitted_at'); // # 17
            $table->string('offensed_date', 100)->nullable()->after('final_submitted_at'); // # 17
            $table->string('offensed_reason', 100)->nullable()->after('final_submitted_at'); // # 17
            $table->string('is_prev_rejected', 10)->nullable()->after('final_submitted_at'); // # 18
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
            $table->dropColumn('is_engaged_in_business');
            $table->dropColumn('is_practice_in_pbc');
            $table->dropColumn('practice_place');
            $table->dropColumn('is_declared_insolvent');
            $table->dropColumn('is_dismissed_from_gov');
            $table->dropColumn('dismissed_reason');
            $table->dropColumn('is_enrolled_as_adv');
            $table->dropColumn('is_offensed');
            $table->dropColumn('offensed_date');
            $table->dropColumn('offensed_reason');
            $table->dropColumn('is_prev_rejected');
        });
    }
}
