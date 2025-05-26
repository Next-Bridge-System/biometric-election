<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntimationsColumnsInApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('llb_passing_year')->nullable();
            $table->unsignedBigInteger('bar_association')->nullable();
            $table->string('blood')->nullable();
            $table->string('srl_name')->nullable();
            $table->string('srl_bar_name')->nullable();
            $table->string('srl_office_address')->nullable();
            $table->string('srl_enr_date')->nullable();
            $table->string('srl_mobile_no')->nullable();
            $table->string('srl_cnic_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            //
        });
    }
}
