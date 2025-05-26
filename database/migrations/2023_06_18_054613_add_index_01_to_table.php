<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndex01ToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table', function (Blueprint $table) {
            Schema::table('lawyer_addresses', function (Blueprint $table) {
                $table->index('application_id');
            });
            Schema::table('lawyer_addresses', function (Blueprint $table) {
                $table->index('lower_court_id');
            });
            Schema::table('lawyer_addresses', function (Blueprint $table) {
                $table->index('high_court_id');
            });
            Schema::table('applications', function (Blueprint $table) {
                $table->index('id');
            });
            Schema::table('lower_courts', function (Blueprint $table) {
                $table->index('id');
            });
            Schema::table('high_courts', function (Blueprint $table) {
                $table->index('id');
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
            Schema::table('lawyer_addresses', function (Blueprint $table) {
                $table->dropIndex('application_id');
            });
            Schema::table('lawyer_addresses', function (Blueprint $table) {
                $table->dropIndex('lower_court_id');
            });
            Schema::table('lawyer_addresses', function (Blueprint $table) {
                $table->dropIndex('high_court_id');
            });
            Schema::table('applications', function (Blueprint $table) {
                $table->dropIndex('id');
            });
            Schema::table('lower_courts', function (Blueprint $table) {
                $table->dropIndex('id');
            });
            Schema::table('high_courts', function (Blueprint $table) {
                $table->dropIndex('id');
            });
        });
    }
}
