<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change02InMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->date('tenure_start_date')->nullable()->after('bar_id');
            $table->date('tenure_end_date')->nullable()->after('tenure_start_date');
            $table->string('signature_url')->nullable()->after('tenure_end_date');
            $table->string('designation')->nullable()->after('signature_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'tenure_start_date','tenure_end_date','signature_url','designation'
            ]);
        });
    }
}
