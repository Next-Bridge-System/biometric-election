<?php

use Illuminate\Database\Seeder;
use App\Tehsil;
use Carbon\Carbon;

class TehsilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tehsil::Insert([
            [
                "id" => 1,
                "district_id" => 1,
                "name" => "LHR",
                "status" => true,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
                "code" => "1234",

            ],
            [
                "id" => 2,
                "district_id" => 2,
                "name" => "FSD",
                "status" => true,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
                "code" => "1234",
            ],
            [
                "id" => 3,
                "district_id" => 3,
                "name" => "RWP",
                "status" => true,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
                "code" => "1234",
            ],
        ]);
    }
}
