<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use MyCLabs\Enum\Enum;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('application_type');
            $table->string('name', 100);
            $table->string('father_name', 100);
            $table->date('date_of_birth');
            $table->float('age');
            $table->string('station', 100)->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('cnic_no', 100)->unique();
            $table->string('home_address', 100)->nullable();
            $table->string('postal_address', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contact', 100)->unique();
            $table->string('degree_type', 100)->nullable();
            $table->integer('otp')->unsigned()->nullable();
            $table->timestamp('otp_created_at')->nullable();
            $table->timestamp('otp_verified_at')->nullable();
            $table->enum('payment_status', ['PAID', 'UNPAID', 'PARTIAL'])->default('UNPAID');
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
        Schema::dropIfExists('vouchers');
    }
}
