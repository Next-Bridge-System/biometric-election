<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('complaint_id');
            // $table->foreign('complaint_id')->references('id')->on('complaints')->onDelete('cascade');
            $table->string('file_url');
            $table->string('file_name');
            $table->boolean('status')->default(TRUE);
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
        Schema::dropIfExists('complaint_files');
    }
}
