<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnBackColumnInVppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vpp', function (Blueprint $table) {
            $table->boolean('vpp_return_back')->default(false);
            $table->timestamp('vpp_return_back_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vpp', function (Blueprint $table) {
            //
        });
    }
}
