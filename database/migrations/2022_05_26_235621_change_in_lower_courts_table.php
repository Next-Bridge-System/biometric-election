<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInLowerCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lower_courts', function (Blueprint $table) {
            $table->string('srl_name', 100)->nullable()->after('llb_passing_year');
            $table->string('srl_bar_id', 100)->nullable()->after('srl_name');
            $table->string('srl_office_address', 100)->nullable()->after('srl_bar_id');
            $table->string('srl_enr_date', 100)->nullable()->after('srl_office_address');
            $table->string('srl_mobile_no', 100)->nullable()->after('srl_enr_date');
            $table->string('srl_cnic_no', 100)->nullable()->after('srl_mobile_no');
            $table->string('srl_joining_date', 100)->nullable()->after('srl_cnic_no');
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
            $table->dropColumn('srl_name');
            $table->dropColumn('srl_bar_id');
            $table->dropColumn('srl_office_address');
            $table->dropColumn('srl_enr_date');
            $table->dropColumn('srl_mobile_no');
            $table->dropColumn('srl_cnic_no');
            $table->dropColumn('srl_joining_date');
        });
    }
}
