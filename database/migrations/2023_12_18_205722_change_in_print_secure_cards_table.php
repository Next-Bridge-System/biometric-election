<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInPrintSecureCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('print_secure_cards', function (Blueprint $table) {
            $table->enum('application_type', ['lc', 'hc', 'gc_lc', 'gc_hc'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('print_secure_cards', function (Blueprint $table) {
            $table->dropColumn('application_type');
        });
    }
}
