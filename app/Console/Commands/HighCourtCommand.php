<?php

namespace App\Console\Commands;

use App\HighCourt;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class HighCourtCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:high-court';

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
        $highcourts = HighCourt::all();
        foreach ($highcourts as $key => $hc) {
            $hc->update([
                'dob_new' => isset($hc->dob) ? Carbon::parse($hc->dob)->format('Y-m-d') : NULL,
                'cnic_exp_new' => isset($hc->cnic_exp_date) ? Carbon::parse($hc->cnic_exp_date)->format('Y-m-d') : NULL,
            ]);
            $user = User::where('id', $hc->user_id)->first();
            $user->update([
                'father_name' => $hc->father_name,
                'date_of_birth' => $hc->dob_new,
                'gender' => $hc->gender,
                'blood' => $hc->blood,
                'cnic_expired_at' => $hc->cnic_exp_new,
            ]);

            dump($key);
        }
    }
}
