<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawyerUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lawyer_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->string('profile_image')->nullable();
            $table->string('cnic_front')->nullable();
            $table->string('cnic_back')->nullable();
            $table->string('card_front')->nullable();
            $table->string('card_back')->nullable();
            // FOR EXISTING LAWYERS
            $table->string('certificate_lc')->nullable();
            $table->string('affidavit_lc')->nullable();
            $table->string('cases_lc')->nullable();
            $table->string('voucher_lc')->nullable();
            $table->string('gat_lc')->nullable();
            $table->string('certificate_hc')->nullable();
            $table->string('affidavit_hc')->nullable();
            $table->string('cases_hc')->nullable();
            $table->string('voucher_hc')->nullable();
            $table->string('gat_hc')->nullable();
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
        Schema::dropIfExists('lawyer_uploads');
    }
}
