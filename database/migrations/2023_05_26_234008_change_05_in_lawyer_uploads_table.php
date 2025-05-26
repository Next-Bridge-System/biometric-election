<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change05InLawyerUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyer_uploads', function (Blueprint $table) {
            $table->string('certificate2_hc')->nullable()->after('certificate_hc');
            $table->string('lc_card_front')->nullable();
            $table->string('lc_card_back')->nullable();
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
            $table->dropColumn('certificate2_hc');
            $table->dropColumn('lc_card_front');
            $table->dropColumn('lc_card_back');
        });
    }
}
