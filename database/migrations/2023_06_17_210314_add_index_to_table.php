<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table', function (Blueprint $table) {
            Schema::table('lower_courts', function (Blueprint $table) {
                $table->index('user_id');
            });
            Schema::table('gc_lower_courts', function (Blueprint $table) {
                $table->index('user_id');
            });
            Schema::table('high_courts', function (Blueprint $table) {
                $table->index('user_id');
            });
            Schema::table('gc_high_courts', function (Blueprint $table) {
                $table->index('user_id');
            });
            Schema::table('users', function (Blueprint $table) {
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
            Schema::table('lower_courts', function (Blueprint $table) {
                $table->dropIndex('user_id');
            });
            Schema::table('gc_lower_courts', function (Blueprint $table) {
                $table->dropIndex('user_id');
            });
            Schema::table('high_courts', function (Blueprint $table) {
                $table->dropIndex('user_id');
            });
            Schema::table('gc_high_courts', function (Blueprint $table) {
                $table->dropIndex('user_id');
            });
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('id');
            });
        });
    }
}
