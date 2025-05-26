<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change03InLowerCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lower_courts', function (Blueprint $table) {
            $table->boolean('is_exemption')->default(0)->after('final_submitted_at');
            $table->unsignedBigInteger('exemption_reason')->nullable()->after('is_exemption');
            $table->unsignedBigInteger('degree_place')->nullable()->after('exemption_reason'); // 1 = Punjab,2 = Out Of Punjab, 3 = Out of Pakista
            $table->boolean('bf_opt_scheme')->default(0)->after('degree_place');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lower_courts', function (Blueprint $table) {
            $table->dropColumn('is_exemption');
            $table->dropColumn('exemption_reason');
            $table->dropColumn('degree_place');
            $table->dropColumn('bf_opt_scheme');
        });
    }
}
