<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change01InLawyerEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyer_education', function (Blueprint $table) {
            $table->string('gat_pass')->nullable()->after('certificate');
            $table->boolean('is_exemption_eligible')->nullable()->after('gat_pass');
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
            $table->dropColumn('gat_pass');
            $table->dropColumn('is_exemption_eligible');

        });
    }
}
