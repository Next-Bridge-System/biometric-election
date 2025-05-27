<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name_english');
            $table->string('name_urdu')->nullable();
            $table->string('image_url')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0=Inactive, 1=Active');
            $table->foreignId('created_by')->constrained('admins')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('seats');
    }
}
