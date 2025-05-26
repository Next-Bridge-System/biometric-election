<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInBiometricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('biometrics', function (Blueprint $table) {
            $table->string('finger', 100)->nullable()->after('uri');
            $table->bigInteger('created_by')->unsigned()->after('status');
            $table->bigInteger('updated_by')->unsigned()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('biometrics', function (Blueprint $table) {
            $table->dropColumn('finger');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
}
