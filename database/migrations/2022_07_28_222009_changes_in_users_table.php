<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 100)->nullable()->change();
            $table->string('fname', 100)->nullable()->change();
            $table->string('lname', 100)->nullable()->change();
            $table->string('email', 100)->nullable()->change();
            $table->string('phone', 100)->nullable()->change();
            $table->boolean('is_excel')->nullable()->default(false);
            $table->string('sr_no', 50)->nullable()->unique();
            $table->string('phone', 50)->nullable()->unique()->change();
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
            $table->dropColumn('is_excel');
            $table->dropColumn('sr_no');
            $table->dropUnique('users_phone_unique');
        });
    }
}
