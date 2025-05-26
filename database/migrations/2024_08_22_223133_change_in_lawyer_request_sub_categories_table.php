<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInLawyerRequestSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyer_request_sub_categories', function (Blueprint $table) {
            $table->enum('type', ['general', 'lc', 'hc'])->nullable()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lawyer_request_sub_categories', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
