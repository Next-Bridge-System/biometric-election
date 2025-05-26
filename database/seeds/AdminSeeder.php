<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::insert([
            [
                'name' =>'Super Admin',
                'email' =>'admin@pbbarcouncil.com',
                'password'=>Hash::make('rx\Z2Y\"$GN.'),
                'phone'=>'1234567890',
                'is_super'=> 1,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ],
        ]);
    }
}
