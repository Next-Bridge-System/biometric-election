<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInPrintSecureCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('print_secure_cards', function (Blueprint $table) {
            $table->string('vvp_number')->nullable()->after('printed_at');
            $table->boolean('vvp_delivered')->nullable()->after('printed_at');
            $table->boolean('vvp_returned')->nullable()->after('printed_at');
            $table->string('fees_year')->nullable()->after('printed_at');
            $table->string('total_dues')->nullable()->after('printed_at');
            $table->text('remarks')->nullable()->after('printed_at');
            $table->boolean('is_duplicate')->nullable()->after('printed_at');
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
            $table->dropColumn('vvp_number');
            $table->dropColumn('vvp_delivered');
            $table->dropColumn('vvp_returned');
            $table->dropColumn('fees_year');
            $table->dropColumn('total_dues');
            $table->dropColumn('remarks');
            $table->dropColumn('is_duplicate');
        });
    }
}
