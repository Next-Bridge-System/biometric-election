<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGcLowerCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gc_lower_courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('sr_no_lc', 100)->nullable();
            $table->string('lawyer_name', 100)->nullable();
            $table->string('father_name', 100)->nullable();
            $table->string('gender', 100)->nullable();
            $table->string('date_of_birth', 100)->nullable();
            $table->string('age', 100)->nullable();
            $table->string('cnic_no', 100)->nullable();
            $table->string('reg_no_lc', 100)->nullable();
            $table->string('license_no_lc', 100)->nullable();
            $table->string('bf_no_lc', 100)->nullable();
            $table->string('date_of_enrollment_lc', 100)->nullable();
            $table->string('voter_member_lc', 100)->nullable();
            $table->string('image', 100)->nullable();
            $table->string('rf_id', 100)->nullable();
            $table->string('address_1', 100)->nullable();
            $table->string('address_2', 100)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contact_no', 100)->nullable();
            $table->string('enr_app_sdw', 100)->nullable();
            $table->string('enr_status_reason', 100)->nullable();
            $table->string('enr_plj_check', 100)->nullable();
            $table->string('enr_gi_check', 100)->nullable();
            $table->integer('app_status')->default(1);
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gc_lower_courts');
    }
}
