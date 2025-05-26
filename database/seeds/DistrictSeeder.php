<?php

use Illuminate\Database\Seeder;
use App\District;
use Carbon\Carbon;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        District::Insert([
            [
                "id" => 1,
                "division_id" => 1,
                "name" => "Lahore",
                "code" => "1234",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),

            ],
            [
                "id" => 2,
                "division_id" => 1,
                "name" => "Faisalabad",
                "code" => "1234",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id" => 3,
                "division_id" => 1,
                "name" => "Rawalpindi",
                "code" => "1234",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
        ]);
    }
}
