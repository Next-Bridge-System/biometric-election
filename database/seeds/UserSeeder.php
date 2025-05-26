<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::insert([
        //     [
        //         'name' =>'Moiz Chauhdry',
        //         'fname' =>'Moiz',
        //         'lname' =>'Chauhdry',
        //         'email' =>'moizchauhdry@gmail.com',
        //         'password'=>Hash::make('password'),
        //         'phone'=>'3204650584',
        //         'access_token'=> getAccessToken(),
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
        // ]);


        $data = [];

        for ($i=0; $i < 90000 ; $i++) {
            $data [] =  [
                'name' =>'Moiz Chauhdry - '.$i,
                'fname' =>'Moiz-'.$i,
                'lname' =>'Chaudhry-'.$i,
                'email' =>'moizchauhdry-'.$i.'@gmail.com',
                'password'=>'1234567'.$i,
                'phone'=> '320465058'.$i,
                'cnic_no'=> '35202-3689597-'.$i,
                'phone_verified_at'=> Carbon::now(),
                'otp'=> '0000'.$i,
                'access_token'=> getAccessToken(),
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ];
        }

        $chunks = array_chunk($data, 5000);
        foreach ($chunks as $chunk) {
            User::insert($chunk);
        }
    }
}
