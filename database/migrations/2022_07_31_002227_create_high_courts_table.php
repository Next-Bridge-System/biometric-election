<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHighCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('high_courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('sr_no_hc', 100)->nullable()->unique();
            $table->string('hcr_no_hc', 100)->nullable()->unique();
            $table->string('father_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('cnic_no')->nullable();
            $table->string('cnic_exp_date')->nullable();
            $table->float('age')->nullable();
            $table->string('blood')->nullable();
            $table->string('license_no_hc')->nullable();
            $table->string('bf_no_hc')->nullable();
            $table->string('enr_date_hc')->nullable();
            $table->string('enr_date_lc')->nullable();
            $table->string('voter_member_hc')->nullable();
            $table->integer('llb_year')->nullable();
            $table->integer('app_status')->default(1); // Active, Suspended, Died, Removed, Transfer in, Transfer out etc
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_frontend')->default(false);
            $table->boolean('is_academic')->default(false);
            $table->boolean('is_final_submitted')->default(false);
            $table->timestamp('final_submitted_at')->nullable();
            $table->bigInteger('role')->unsigned()->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->boolean('is_excel')->default(false);
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
        Schema::dropIfExists('high_courts');
    }
}
