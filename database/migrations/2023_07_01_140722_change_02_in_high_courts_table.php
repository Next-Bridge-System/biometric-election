<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change02InHighCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('high_courts', function (Blueprint $table) {
            $table->tinyInteger('app_type')->after('app_status');
            $table->json('objections')->nullable();
            $table->date('rcpt_date')->nullable();
            $table->integer('rcpt_no_hc')->unsigned()->nullable();
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
            $table->dropColumn('app_type');
            $table->dropColumn('objections');
            $table->dropColumn('rcpt_date');
            $table->dropColumn('rcpt_no_hc');
        });
    }
}
