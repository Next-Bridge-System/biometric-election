<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliceVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('police_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('cnic', 100)->unique();
            $table->boolean('verified')->nullable()->default(false);
            $table->boolean('verified_at')->nullable()->default(false);
            $table->bigInteger('verified_by')->nullable();
            $table->longText('data')->nullable();
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
        Schema::dropIfExists('police_verifications');
    }
}
