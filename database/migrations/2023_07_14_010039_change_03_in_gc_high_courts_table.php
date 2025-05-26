<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change03InGcHighCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gc_high_courts', function (Blueprint $table) {
            $table->integer('rcpt_no_hc')->unsigned()->nullable();
            $table->date('rcpt_date')->nullable();
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
