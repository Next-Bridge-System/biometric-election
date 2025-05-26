<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_statuses', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('key');
            $table->string('value', 100);
            $table->enum('type', ['intimation', 'lc', 'hc'])->nullable();
            $table->boolean('status')->nullable()->default(true);
            $table->string('label', 100)->nullable();
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
        Schema::dropIfExists('app_statuses');
    }
}
