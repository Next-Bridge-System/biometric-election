<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucherPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('vch_no', 100);
            $table->string('vch_name', 100);
            $table->tinyInteger('vch_type');
            $table->double('vch_amount', 8, 2);
            $table->boolean('vch_payment_status')->nullable()->default(false);
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
        Schema::dropIfExists('voucher_payments');
    }
}
