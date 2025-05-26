<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawyerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lawyer_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lawyer_request_category_id')->unsigned();
            $table->bigInteger('lawyer_request_sub_category_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('lawyer_type', 50);
            $table->string('lawyer_name');
            $table->string('father_name');
            $table->string('cnic_no');
            $table->string('license_no')->nullable();
            $table->string('enr_date_lc')->nullable();
            $table->string('enr_date_hc')->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('bar_id')->unsigned()->nullable();
            $table->string('embassy_name')->nullable();
            $table->string('member_of')->nullable();
            $table->string('visit_country')->nullable();
            $table->string('city')->nullable();
            $table->double('amount')->default(0);
            $table->boolean('status')->nullable();
            $table->boolean('approved')->default(0);
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
        Schema::dropIfExists('lawyer_requests');
    }
}
