<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInGcHighCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gc_high_courts', function (Blueprint $table) {
            $table->string('sr_no_hc', 100)->unique()->change();
            $table->date('enr_date_hc')->nullable()->change();
            $table->date('enr_date_lc')->nullable()->change();
            $table->date('date_of_birth')->nullable()->change();
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
            //
        });
    }
}
