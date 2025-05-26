<?php

namespace App\Console\Commands;

use App\GcHighCourt;
use App\GcLowerCourt;
use App\User;
use Illuminate\Console\Command;

class gc_import_command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-gc-users';

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
        // $hc_users = User::where('register_as', 'gc_hc')->get();
        // foreach ($hc_users as $key => $user) {
        //     $gc_hc = GcHighCourt::where('sr_no_hc', $user->sr_no_hc)->first();
        //     if ($gc_hc) {
        //         $gc_hc->update(['user_id' => $user->id]);
        //         if ($user->gc_status == 'approved') {
        //             $gc_hc->update([
        //                 'cnic_no' => $user->cnic_no,
        //                 'contact_no' => $user->phone,
        //             ]);
        //         }
        //     }
        //     dump('GC HIGH COURT UPDATE ... ' . $key);
        // }

        // $lc_users = User::where('register_as', 'gc_lc')->get();
        // foreach ($lc_users as $key => $user) {
        //     $gc_lc = GcLowerCourt::where('sr_no_lc', $user->sr_no_lc)->first();
        //     if ($gc_lc) {
        //         $gc_lc->update(['user_id' => $user->id]);
        //         if ($user->gc_status == 'approved') {
        //             $gc_lc->update([
        //                 'cnic_no' => $user->cnic_no,
        //                 'contact_no' => $user->phone,
        //             ]);
        //         }
        //     }
        //     dump('GC LOWER COURT UPDATE ... ' . $key);
        // }

        $users = User::where('register_as', 'gc_lc')->get();
        foreach ($users as $key => $user) {
            $gc_lc = GcLowerCourt::where('sr_no_lc', $user->sr_no_lc)->where('app_status', 8)->first();
            if ($gc_lc) {
                $user->update(['register_as' => 'gc_hc']);
                $gc_hc = GcHighCourt::where('lc_ledger', $gc_lc->reg_no_lc)->where('app_status', 8)->first();
                if ($gc_hc) {
                    $gc_hc->update(['user_id' => $user->id]);
                }

                dump('USER TYPE UPDATE GC-LC => GC-HC ... ' . $gc_lc->reg_no_lc);
            }
        }
    }
}
