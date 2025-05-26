<?php

use App\LawyerRequestCategory;
use App\LawyerRequestSubCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LawyerRequestCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lawyer_request_categories = [
            [
                'name' => 'Certificates',
                'slug' => 'certificates',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Duplicate Identity Card',
                'slug' => 'duplicate-identity-card',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($lawyer_request_categories as $key => $lawyer_request_category) {
            LawyerRequestCategory::updateOrCreate(['slug' => $lawyer_request_category['slug']], $lawyer_request_category);
        }

        $lawyer_request_sub_categories = [
            [
                'lawyer_request_category_id' => 1,
                'name' => 'Certificate for grant of Visas/QLTT',
                'slug' => 'visa-certificates',
                'amount' => 3000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lawyer_request_category_id' => 1,
                'name' => 'Character Certificates',
                'slug' => 'character-certificates',
                'amount' => 1500,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lawyer_request_category_id' => 1,
                'name' => 'Experience Certificate Lower Court',
                'slug' => 'experience-certificate-lower-court',
                'amount' => 1000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lawyer_request_category_id' => 1,
                'name' => 'Experience Certificate High Court',
                'slug' => 'experience-certificate-high-court',
                'amount' => 1500,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lawyer_request_category_id' => 1,
                'name' => 'Certificate to foreign Law Society',
                'slug' => 'certificates-to-foreign-law-soceity',
                'amount' => 2500,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lawyer_request_category_id' => 2,
                'name' => 'Duplicate High Court Card',
                'slug' => 'duplicate-high-court-card',
                'amount' => 1000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lawyer_request_category_id' => 2,
                'name' => 'Supreme Court Certificate Fee',
                'slug' => 'supreme_court_certificate_fee',
                'amount' => 4000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'lawyer_request_category_id' => 2,
                'name' => 'Subsequent Certificate for Supreme Court',
                'slug' => 'subsequent_certificate_for_supreme_court',
                'amount' => 1500,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($lawyer_request_sub_categories as $key => $lawyer_request_sub_category) {
            LawyerRequestSubCategory::updateOrCreate(['slug' => $lawyer_request_sub_category['slug']], $lawyer_request_sub_category);
        }
    }
}
