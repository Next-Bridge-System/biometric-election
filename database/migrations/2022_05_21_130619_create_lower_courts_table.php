<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLowerCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lower_courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('father_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('cnic_no')->nullable();
            $table->string('cnic_expiry_date')->nullable();
            $table->float('age')->nullable();
            $table->string('blood')->nullable();
            $table->string('reg_no_lc')->nullable();
            $table->string('sr_no_lc')->nullable();
            $table->string('license_no_lc')->nullable();
            $table->string('bf_no_lc')->nullable();
            $table->string('date_of_enrollment_lc')->nullable();
            $table->string('voter_member_lc')->nullable();
            $table->integer('llb_passing_year')->nullable();
            $table->integer('app_status')->default(1); // Active, Suspended, Died, Removed, Transfer in, Transfer out.
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_frontend')->default(false);
            $table->boolean('is_academic_record')->default(false);
            $table->boolean('is_final_submitted')->default(false);
            $table->timestamp('final_submitted_at')->nullable();
            $table->bigInteger('role')->unsigned()->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('lower_courts');
    }
}
