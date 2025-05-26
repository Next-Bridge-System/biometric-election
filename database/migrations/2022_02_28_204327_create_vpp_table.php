<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vpp', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('application_id')->unsigned();
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->bigInteger('admin_id')->unsigned();
            $table->string('vpp_number')->nullable();
            $table->boolean('vpp_delivered')->default(false);
            $table->boolean('vpp_returned')->default(false);
            $table->string('vpp_fees_year')->nullable();
            $table->string('vpp_total_dues')->nullable();
            $table->text('vpp_remarks')->nullable();
            $table->boolean('vpp_duplicate')->default(false);
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
        Schema::dropIfExists('vpp');
    }
}
