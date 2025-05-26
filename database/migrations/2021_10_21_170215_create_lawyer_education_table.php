<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawyerEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lawyer_education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->string('qualification')->nullable();
            $table->string('sub_qualification')->nullable();
            $table->unsignedBigInteger('university_id')->nullable();
            $table->string('institute')->nullable();
            $table->float('total_marks')->nullable();
            $table->float('obtained_marks')->nullable();
            $table->integer('passing_year')->nullable();
            $table->string('roll_no')->nullable();
            $table->string('certificate')->nullable();
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
        Schema::dropIfExists('lawyer_education');
    }
}
