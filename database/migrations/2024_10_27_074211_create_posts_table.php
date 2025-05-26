<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('lawyer_name', 100)->nullable()->index();
            $table->string('father_husband', 100)->nullable()->index();
            $table->string('address', 100)->nullable();

            $table->string('cnic_no', 100)->nullable();
            $table->bigInteger('clean_cnic_no')->nullable()->index();
            
            $table->string('mobile_no', 100)->nullable();
            $table->bigInteger('clean_mobile_no')->nullable()->index();

            $table->string('webcam_image_url')->nullable();
            $table->integer('rcpt_no')->unsigned()->nullable();
            $table->date('rcpt_date')->nullable();
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
        Schema::dropIfExists('posts');
    }
}
