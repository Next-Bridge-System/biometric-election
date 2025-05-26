<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGcHighCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gc_high_courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('sr_no_hc', 100)->nullable();
            $table->string('lawyer_name', 100)->nullable();
            $table->string('father_name', 100)->nullable();
            $table->string('hcr_no_hc', 100)->nullable();
            $table->string('license_no_hc', 100)->nullable();
            $table->string('bf_no_hc', 100)->nullable();
            $table->string('enr_date_hc', 100)->nullable();
            $table->string('date_of_birth', 100)->nullable();
            $table->string('age', 100)->nullable();
            $table->string('cnic_no', 100)->nullable();
            $table->string('gender', 100)->nullable();
            $table->string('address_1', 100)->nullable();
            $table->string('address_2', 100)->nullable();
            $table->string('enr_date_lc', 100)->nullable();
            $table->string('contact_no', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->tinyInteger('enr_status_type')->nullable();
            $table->string('enr_status_reason')->nullable();
            $table->string('lc_ledger', 100)->nullable();
            $table->tinyInteger('lc_sdw')->nullable();
            $table->string('lc_last_status')->nullable();
            $table->string('voter_member_hc', 100)->nullable();
            $table->string('image', 100)->nullable();
            $table->string('lc_lic', 100)->nullable();
            $table->tinyInteger('app_status')->nullable();
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
        Schema::dropIfExists('gc_high_courts');
    }
}
