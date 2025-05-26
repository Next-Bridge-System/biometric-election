<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInBiometricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('biometrics', function (Blueprint $table) {
            $table->string('lawyer_name', 100)->nullable()->after('user_id');
            $table->string('cnic_no', 100)->nullable()->after('lawyer_name');
            $table->integer('finger_id')->unsigned()->nullable()->after('cnic_no');
            $table->string('finger_name', 100)->nullable()->after('finger_id');
            $table->json('fingerprint_response_1')->nullable()->after('finger_name');;
            $table->json('fingerprint_response_2')->nullable()->after('fingerprint_response_1');;
            $table->text('template_1')->nullable()->after('fingerprint_response_2');;
            $table->text('template_2')->nullable()->after('template_1');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('biometrics', function (Blueprint $table) {
            $table->dropColumn('lawyer_name');
            $table->dropColumn('cnic_no');
            $table->dropColumn('finger_id');
            $table->dropColumn('finger_name');
            $table->dropColumn('fingerprint_response_1');
            $table->dropColumn('fingerprint_response_2');
            $table->dropColumn('template_1');
            $table->dropColumn('template_2');
        });
    }
}
