<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            // Application Information
            $table->integer('application_token_no')->nullable();
            $table->integer('application_type')->nullable();
            $table->integer('application_status')->default(1); // Active, Suspended, Died, Removed, Transfer in, Transfer out.
            $table->integer('card_status')->default(1); // Pending, Printing, Dispatch.
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_excel_import')->default(FALSE);
            $table->string('print_date')->nullable();
            $table->boolean('is_approved')->default(TRUE);

            // Personal Information
            $table->string('advocates_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('so_of')->nullable();
            $table->string('gender')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('profile_image_url')->nullable();
            $table->string('profile_image_name')->nullable();
            $table->string('email')->nullable();

            // Contact Infromation
            $table->string('cnic_no')->nullable();
            // $table->unique('cnic_no');
            $table->string('cnic_expiry_date')->nullable();
            $table->string('whatsapp_no')->nullable();
            $table->string('active_mobile_no')->nullable();

            // Address Information
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('tehsil_id')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('address_2')->nullable();

            // Lawyer Information
            $table->string('reg_no_lc')->nullable();
            $table->string('high_court_roll_no')->nullable();
            $table->string('sr_no_lc')->nullable();
            $table->string('sr_no_hc')->nullable();
            $table->string('license_no_lc')->nullable();
            $table->string('license_no_hc')->nullable();
            $table->string('bf_no_lc')->nullable();
            $table->string('bf_no_hc')->nullable();
            $table->string('plj_no_lc')->nullable();
            $table->string('rf_id')->nullable();
            $table->string('date_of_enrollment_lc')->nullable();
            $table->string('date_of_enrollment_hc')->nullable();
            $table->string('voter_member_lc')->nullable();
            $table->string('voter_member_hc')->nullable();
            $table->string('hcr_no')->nullable();

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
        Schema::dropIfExists('applications');
    }
}
