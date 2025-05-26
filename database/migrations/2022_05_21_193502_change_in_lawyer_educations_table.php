<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInLawyerEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyer_education', function (Blueprint $table) {
            $table->bigInteger('lower_court_id')->nullable()->unsigned()->after('application_id');
            $table->bigInteger('application_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lawyer_education', function (Blueprint $table) {
            $table->dropColumn('lower_court_id');
        });
    }
}
