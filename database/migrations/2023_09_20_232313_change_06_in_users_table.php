<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Change06InUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('father_name', 100)->nullable()->after('name');
            $table->date('date_of_birth')->nullable()->after('father_name');
            $table->string('gender', 100)->nullable()->after('date_of_birth');
            $table->string('blood', 5)->nullable()->after('gender');
            $table->date('cnic_expired_at')->nullable()->after('cnic_no');
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
            $table->dropColumn('father_name');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('gender');
            $table->dropColumn('blood');
            $table->dropColumn('cnic_expired_at');
        });
    }
}
