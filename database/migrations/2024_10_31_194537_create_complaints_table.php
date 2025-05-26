<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            
            $table->string('complainant_cnic', 100)->nullable();
            $table->string('complainant_name', 100)->nullable();
            $table->string('complainant_father', 100)->nullable();
            $table->string('complainant_phone', 100)->nullable();

            $table->string('complainant_profile_url', 100)->nullable();
            $table->string('complainant_cnic_front_url', 100)->nullable();
            $table->string('complainant_cnic_back_url', 100)->nullable();

            $table->string('defendant_cnic', 100)->nullable();
            $table->string('defendant_name', 100)->nullable();
            $table->string('defendant_father', 100)->nullable();
            $table->string('defendant_phone', 100)->nullable();
            
            $table->text('additional_info')->nullable();
            
            $table->dateTime('hearing_at')->nullable();
            $table->enum('status', ['open', 'hearing', 'close'])->nullable()->default('open');
            $table->enum('payment_status', ['pending', 'paid', 'partial'])->nullable()->default('pending');
            $table->enum('voucher_status', ['pending', 'attached'])->nullable()->default('pending');

            $table->decimal('amount', 5, 2)->nullable();
            
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
        Schema::dropIfExists('complaints');
    }
}
