<?php

use App\ComplaintStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('complaint_statuses')->truncate();

        $complaint_status = [
            ['id' => 1, 'badge' => 'primary', 'name' => 'Open'],
            ['id' => 2, 'badge' => 'warning', 'name' => 'Hearing'],
            ['id' => 3, 'badge' => 'success', 'name' => 'Close'],
            ['id' => 4, 'badge' => 'danger', 'name' => 'Rejected'],
        ];

        foreach ($complaint_status as $key => $data) {
            ComplaintStatus::create($data);
        }
    }
}
