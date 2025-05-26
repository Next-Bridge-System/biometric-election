<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change04InLawyerUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyer_uploads', function (Blueprint $table) {
            $table->string('lc_plj_br_cnic')->nullable();
            $table->string('lc_plj_br_license')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lawyer_uploads', function (Blueprint $table) {
            $table->dropColumn('lc_plj_br_cnic');
            $table->dropColumn('lc_plj_br_license');
        });
    }
}
