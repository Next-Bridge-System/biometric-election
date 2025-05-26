<?php

use App\ComplaintType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('complaint_types')->truncate();

        $complaint_types = [
            ['id' => 1, 'name' => 'Bar to Bar', 'committee' => 'EC'],
            ['id' => 2, 'name' => 'Lawyer to Lawyer', 'committee' => 'EC'],

            ['id' => 3, 'name' => 'Person to Lawyer', 'committee' => 'DC'],
            ['id' => 4, 'name' => 'Lawyer to Lawyer', 'committee' => 'DC'],

            ['id' => 5, 'name' => 'Person to Lawyer', 'committee' => 'AC'],
            ['id' => 6, 'name' => 'Lawyer to Lawyer', 'committee' => 'AC'],
        ];


        foreach ($complaint_types as $key => $data) {
            ComplaintType::create($data);
        }
    }
}
