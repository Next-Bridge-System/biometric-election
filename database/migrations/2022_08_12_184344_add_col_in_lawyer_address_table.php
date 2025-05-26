<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColInLawyerAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyer_addresses', function (Blueprint $table) {
            $table->bigInteger('high_court_id')->nullable()->unsigned()->after('application_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lawyer_addresses', function (Blueprint $table) {
            $table->dropColumn('high_court_id');
        });
    }
}
