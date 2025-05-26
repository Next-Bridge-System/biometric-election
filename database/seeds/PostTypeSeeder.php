<?php

use App\PostType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_types')->truncate();

        $post_types = [
            ['id' => 1, 'name' => 'Executive Committee'],
            ['id' => 2, 'name' => 'Misconduct committee'],
            ['id' => 3, 'name' => 'Anti corruption committee'],
            ['id' => 4, 'name' => 'High Court Record Section'],
            ['id' => 5, 'name' => 'Intimation section'],
            ['id' => 6, 'name' => 'Enrollment Lower court section'],
            ['id' => 7, 'name' => 'Enrollment High court section'],
            ['id' => 8, 'name' => 'Renewal section'],
            ['id' => 9, 'name' => 'Card section'],
            ['id' => 10, 'name' => 'I.T section'],
            ['id' => 11, 'name' => 'V.C'],
            ['id' => 12, 'name' => 'CEC'],
            ['id' => 13, 'name' => 'Members'],
            ['id' => 14, 'name' => 'Secretary'],
            ['id' => 15, 'name' => 'Additional Secretary'],
            ['id' => 16, 'name' => 'Benevolent fund section'],
            ['id' => 17, 'name' => 'Group Insurance section'],
            ['id' => 18, 'name' => 'Account section'],
            ['id' => 19, 'name' => 'PLJ section'],
            ['id' => 20, 'name' => 'Editor PLJ'],
            ['id' => 21, 'name' => 'Admin section'],
            ['id' => 22, 'name' => 'Other'],
        ];


        foreach ($post_types as $key => $data) {
            PostType::create($data);
        }
    }
}
