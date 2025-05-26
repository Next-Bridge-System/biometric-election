<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->tinyInteger('application_type')->nullable();
            $table->tinyInteger('application_status')->nullable();
            $table->tinyInteger('payment_status')->nullable();
            $table->tinyInteger('payment_type')->nullable();
            $table->double('amount')->nullable();
            $table->string('voucher_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('voucher_file')->nullable();
            $table->string('paid_date')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
