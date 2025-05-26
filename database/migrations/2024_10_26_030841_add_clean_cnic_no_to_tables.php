<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCleanCnicNoToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('clean_cnic_no')->nullable()->after('cnic_no');
            $table->index('clean_cnic_no', 'users_clean_cnic_no_index');;
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->bigInteger('clean_cnic_no')->nullable()->after('cnic_no');
            $table->index('clean_cnic_no', 'applications_clean_cnic_no_index');;
        });

        Schema::table('lower_courts', function (Blueprint $table) {
            $table->bigInteger('clean_cnic_no')->nullable()->after('cnic_no');
            $table->index('clean_cnic_no', 'lower_courts_clean_cnic_no_index');;
        });

        Schema::table('gc_lower_courts', function (Blueprint $table) {
            $table->bigInteger('clean_cnic_no')->nullable()->after('cnic_no');
            $table->index('clean_cnic_no', 'gc_lower_courts_clean_cnic_no_index');;
        });

        // Schema::table('high_courts', function (Blueprint $table) {
        //     $table->bigInteger('clean_cnic_no')->nullable()->after('cnic_no');
        //     $table->index('clean_cnic_no', 'high_courts_clean_cnic_no_index');;
        // });

        Schema::table('gc_high_courts', function (Blueprint $table) {
            $table->bigInteger('clean_cnic_no')->nullable()->after('cnic_no');
            $table->index('clean_cnic_no', 'gc_high_courts_clean_cnic_no_index');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_clean_cnic_no_index');
            $table->dropColumn('clean_cnic_no');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('applications_clean_cnic_no_index');
            $table->dropColumn('clean_cnic_no');
        });

        Schema::table('lower_courts', function (Blueprint $table) {
            $table->dropIndex('lower_courts_clean_cnic_no_index');
            $table->dropColumn('clean_cnic_no');
        });

        Schema::table('gc_lower_courts', function (Blueprint $table) {
            $table->dropIndex('gc_lower_courts_clean_cnic_no_index');
            $table->dropColumn('clean_cnic_no');
        });

        // Schema::table('high_courts', function (Blueprint $table) {
        //     $table->dropIndex('high_courts_clean_cnic_no_index');
        //     $table->dropColumn('clean_cnic_no');
        // });

        Schema::table('gc_high_courts', function (Blueprint $table) {
            $table->dropIndex('gc_high_courts_clean_cnic_no_index');
            $table->dropColumn('clean_cnic_no');
        });
    }
}
