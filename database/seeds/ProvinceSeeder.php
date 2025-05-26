<?php

use Illuminate\Database\Seeder;
use App\Province;
use Carbon\Carbon;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Province::Insert([
            [
                "id" => 1,
                "name" => "Punjab",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),

            ],
            [
                "id" => 2,
                "name" => "Sindh",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id" => 3,
                "name" => "Balochistan",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id" => 4,
                "name" => "Khyber Pakhtunkhwa",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id" => 5,
                "name" => "Azad Jammu & Kashmir",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id" => 6,
                "name" => "Gilgit Baltistan",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id" => 7,
                "name" => "Islamabad Capital",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
        ]);
    }
}
