<?php

namespace App\Console\Commands;

use App\GcHighCourt;
use App\GcLowerCourt;
use App\User;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

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
        $gc_lower_courts = GcLowerCourt::get();
        foreach ($gc_lower_courts as $key => $gc_lc) {
            $user = User::where('sr_no_lc', $gc_lc->sr_no_lc)->where('register_as', 'lc')->first();

            if ($user == NULL) {
                $user = User::create([
                    'name' =>  $gc_lc->lawyer_name,
                    'email' =>  NULL,
                    'password' =>  'DUMMY PASSWORD',
                    'fname' =>  $gc_lc->name,
                    'phone' =>  NULL,
                    'register_as' =>  'lc',
                    'cnic_no' =>  NULL,
                    'sr_no_lc' =>  $gc_lc->sr_no_lc,
                    'cnic_no' =>  $gc_lc->cnic_no,
                ]);
            } else {
                $user->update([
                    'sr_no_lc' => $gc_lc->sr_no_lc,
                    'cnic_no' => $gc_lc->cnic_no,
                ]);
            }

            $gc_lc->update([
                'user_id' => $user->id,
            ]);

            dump('lc running ....... ' . $key);
        }
    }
}
