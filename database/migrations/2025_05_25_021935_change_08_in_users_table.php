<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change08InUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('webcam_image_path', 100)->nullable();
            $table->tinyInteger('biometric_status')->default(0)->comment('0=Not Registered, 1=Registered, 2=Pending, 3=Rejected');
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
            $table->dropColumn('webcam_image_path');
            $table->dropColumn('biometric_status');
        });
    }
}
