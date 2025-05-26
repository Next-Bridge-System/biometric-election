<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('lower_court_id')->nullable()->after('application_id')->constrained('vouchers', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('voucher_name', 100)->nullable()->default('INTIMATION')->after('voucher_no');
            $table->tinyInteger('voucher_type')->default(1)->after('voucher_name');
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
            $table->dropForeign('payments_lower_court_id_foreign');
            $table->dropColumn('lower_court_id');
            $table->dropColumn('voucher_name');
            $table->dropColumn('voucher_type');
        });
    }
}
