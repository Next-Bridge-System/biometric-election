<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSrLawyersColumnsInLawyerUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyer_uploads', function (Blueprint $table) {
            $table->string('srl_cnic_front')->nullable();
            $table->string('srl_cnic_back')->nullable();
            $table->string('srl_license_front')->nullable();
            $table->string('srl_license_back')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lawyer_uploads', function (Blueprint $table) {
            //
        });
    }
}
