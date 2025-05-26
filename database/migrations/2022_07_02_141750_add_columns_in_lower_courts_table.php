<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInLowerCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lower_courts', function (Blueprint $table) {
            $table->string('rcpt_no_lc', 100)->nullable()->after('bf_no_lc');
            $table->date('rcpt_date')->nullable()->after('rcpt_no_lc');
            $table->string('plj_no_lc', 100)->nullable()->after('bf_no_lc');
            $table->string('gi_no_lc', 100)->nullable()->after('bf_no_lc');
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
            $table->dropColumn('rcpt_no_lc');
            $table->dropColumn('plj_no_lc');
            $table->dropColumn('gi_no_lc');
        });
    }
}
