<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawyerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lawyer_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            // HOME ADDRESS
            $table->string('ha_house_no')->nullable();
            $table->string('ha_str_address')->nullable();
            $table->string('ha_town')->nullable();
            $table->string('ha_city')->nullable();
            $table->integer('ha_postal_code')->nullable();
            $table->unsignedBigInteger('ha_country_id')->nullable();
            $table->unsignedBigInteger('ha_province_id')->nullable();
            $table->unsignedBigInteger('ha_district_id')->nullable();
            $table->unsignedBigInteger('ha_tehsil_id')->nullable();
            // POSTAL ADDRESS
            $table->string('pa_house_no')->nullable();
            $table->string('pa_str_address')->nullable();
            $table->string('pa_town')->nullable();
            $table->string('pa_city')->nullable();
            $table->integer('pa_postal_code')->nullable();
            $table->unsignedBigInteger('pa_country_id')->nullable();
            $table->unsignedBigInteger('pa_province_id')->nullable();
            $table->unsignedBigInteger('pa_district_id')->nullable();
            $table->unsignedBigInteger('pa_tehsil_id')->nullable();
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
        Schema::dropIfExists('lawyer_addresses');
    }
}
