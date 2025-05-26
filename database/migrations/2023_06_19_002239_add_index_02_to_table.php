<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndex02ToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('sr_no_hc');
            });
            Schema::table('users', function (Blueprint $table) {
                $table->index('register_as');
            });
            Schema::table('gc_high_courts', function (Blueprint $table) {
                $table->index('lc_ledger');
            });
            Schema::table('gc_high_courts', function (Blueprint $table) {
                $table->index('app_status');
            });
            Schema::table('gc_lower_courts', function (Blueprint $table) {
                $table->index('reg_no_lc');
            });
            Schema::table('gc_lower_courts', function (Blueprint $table) {
                $table->index('app_status');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('sr_no_hc');
            });
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('register_as');
            });
            Schema::table('gc_high_courts', function (Blueprint $table) {
                $table->dropIndex('lc_ledger');
            });
            Schema::table('gc_high_courts', function (Blueprint $table) {
                $table->dropIndex('app_status');
            });
            Schema::table('gc_lower_courts', function (Blueprint $table) {
                $table->dropIndex('reg_no_lc');
            });
            Schema::table('gc_lower_courts', function (Blueprint $table) {
                $table->dropIndex('app_status');
            });
        });
    }
}
