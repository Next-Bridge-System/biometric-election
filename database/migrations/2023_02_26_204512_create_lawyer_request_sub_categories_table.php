<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawyerRequestSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lawyer_request_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lawyer_request_category_id')->unsigned();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->double('amount')->default(0);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('lawyer_request_sub_categories');
    }
}
