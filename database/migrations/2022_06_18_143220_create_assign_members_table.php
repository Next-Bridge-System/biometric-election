<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_members', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lower_court_id')->unsigned();
            $table->bigInteger('member_id')->unsigned();
            $table->string('code', 100);
            $table->boolean('is_code_verified')->nullable()->default(false);
            $table->timestamp('code_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assign_members');
    }
}
