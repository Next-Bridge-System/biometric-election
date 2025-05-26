<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInHighCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('high_courts', function (Blueprint $table) {
            $table->string('paid_upto_date_renewal', 100)->nullable();
            $table->string('is_practice_in_pbc', 10)->nullable();
            $table->string('is_engaged_in_business', 10)->nullable();
            $table->string('is_declared_insolvent', 10)->nullable();
            $table->string('is_dismissed_from_public_service', 10)->nullable();
            $table->string('is_enrolled_as_advocate', 10)->nullable();
            $table->string('is_prev_rejected', 10)->nullable();
            $table->string('is_any_misconduct', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('high_courts', function (Blueprint $table) {
            $table->dropColumn('paid_upto_date_renewal');
            $table->dropColumn('is_practice_in_pbc');
            $table->dropColumn('is_engaged_in_business');
            $table->dropColumn('is_declared_insolvent');
            $table->dropColumn('is_dismissed_from_public_service');
            $table->dropColumn('is_enrolled_as_advocate');
            $table->dropColumn('is_prev_rejected');
            $table->dropColumn('is_any_misconduct');
        });
    }
}
