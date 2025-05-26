<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change01InPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('is_voucher_print')->default(1);
            $table->float('enr_fee_pbc')->default(0);
            $table->float('id_card_fee')->default(0);
            $table->float('certificate_fee')->default(0);
            $table->float('building_fund')->default(0);
            $table->float('general_fund')->default(0);
            $table->float('degree_fee')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('is_voucher_print');
            $table->dropColumn('enr_fee_pbc');
            $table->dropColumn('id_card_fee');
            $table->dropColumn('certificate_fee');
            $table->dropColumn('building_fund');
            $table->dropColumn('general_fund');
            $table->dropColumn('degree_fee');
        });
    }
}
