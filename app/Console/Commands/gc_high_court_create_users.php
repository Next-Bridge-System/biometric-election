<?php

namespace App\Console\Commands;

use App\GcHighCourt;
use App\GcLowerCourt;
use App\User;
use Exception;
use Illuminate\Console\Command;

class gc_high_court_create_users extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gc_high_court_users:create';

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
        $gc_high_courts = GcHighCourt::get();
        foreach ($gc_high_courts as $key => $gc_hc) {
            try {
                $gc_lower_court = GcLowerCourt::where('reg_no_lc', $gc_hc->lc_ledger)->first();

                if (isset($gc_lower_court) && $gc_hc->lc_ledger != NULL) {
                    $user = User::find($gc_lower_court->user_id);
                    if ($user) {
                        $user->update([
                            'sr_no_hc' => $gc_hc->sr_no_hc,
                            'register_as' => 'hc',
                        ]);
                        $gc_hc->update([
                            'user_id' => $user->id,
                        ]);
                    }
                } else {
                    $user = User::where('sr_no_hc', $gc_hc->sr_no_hc)->where('register_as', 'hc')->first();
                    if ($user == NULL) {
                        $user = User::create([
                            'name' =>  $gc_hc->lawyer_name,
                            'email' =>  NULL,
                            'password' =>  'HC PASSWORD',
                            'fname' =>  $gc_hc->name,
                            'phone' =>  NULL,
                            'register_as' =>  'hc',
                            'cnic_no' =>  NULL,
                            'sr_no_hc' =>  $gc_hc->sr_no_hc,
                        ]);
                    } else {
                        $user->update([
                            'sr_no_hc' => $gc_hc->sr_no_hc,
                        ]);
                    }

                    $gc_hc->update([
                        'user_id' => $user->id,
                    ]);
                }

                if ($key == 50) {
                    throw new Exception("throw new exeption");
                }
            } catch (\Throwable $th) {
                //throw $th;
                dump('exeption ....... ' . $key);
                continue;
            }

            dump('hc running ....... ' . $key);
        }
    }
}
