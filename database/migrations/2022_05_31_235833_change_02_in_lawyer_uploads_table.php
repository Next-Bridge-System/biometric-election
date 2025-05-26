<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change02InLawyerUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyer_uploads', function (Blueprint $table) {
            $table->string('practice_certificate')->nullable()->after('certificate2_lc');
            $table->string('judge_certificate')->nullable()->after('practice_certificate');
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
            $table->dropColumn('practice_certificate');
            $table->dropColumn('judge_certificate');
        });
    }
}
