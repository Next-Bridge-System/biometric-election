<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanCnicCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:clean-cnic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::where('email', 'moizchauhdry01@gmail.com')->first();

        try {
            DB::statement("UPDATE users SET clean_cnic_no = REPLACE(cnic_no, '-', '')");
            DB::statement("UPDATE applications SET clean_cnic_no = REPLACE(cnic_no, '-', '')");
            DB::statement("UPDATE lower_courts SET clean_cnic_no = REPLACE(cnic_no, '-', '')");
            DB::statement("UPDATE gc_lower_courts SET clean_cnic_no = REPLACE(cnic_no, '-', '')");
            DB::statement("UPDATE gc_high_courts SET clean_cnic_no = REPLACE(cnic_no, '-', '')");

            Log::info("clean cnic completed.");

            $mail_data = [
                'subject' => 'CLEAN CNIC COMMAND - SUCCESS',
                'name' => 'Moiz Chauhdry',
                'description' => '<p>clean cnic completed.</p>',
            ];

            sendMailToUser($mail_data, $user);
        } catch (\Throwable $th) {

            $mail_data = [
                'subject' => 'CLEAN CNIC COMMAND - ERROR',
                'name' => 'Moiz Chauhdry',
                'description' => '<p>' . $th->getMessage() . '</p>',
            ];

            sendMailToUser($mail_data, $user);

            Log::error("error in clean cnic command ......... " . $th->getMessage());
        }
    }
}
