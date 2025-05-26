<?php

namespace App\Console\Commands;

use App\HighCourt;
use App\LawyerUpload;
use App\LowerCourt;
use App\User;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UserProfileImageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:user-profile-image';

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
        ini_set('memory_limit', '1024G');

        $lower_courts = LowerCourt::get();
        foreach ($lower_courts as $key => $lc) {
            $lawyer_upload = LawyerUpload::where('lower_court_id', $lc->id)->first();
            if ($lawyer_upload) {
                $user = User::find($lc->user_id);
                $user->update(['profile_image' => $lawyer_upload->profile_image]);
            }
            \dump('lc-' . $key);
        }

        $high_courts = HighCourt::get();
        foreach ($high_courts as $key => $lc) {
            $lawyer_upload = LawyerUpload::where('high_court_id', $lc->id)->first();
            if ($lawyer_upload) {
                $user = User::find($lc->user_id);
                $user->update(['profile_image' => $lawyer_upload->profile_image]);
            }
            \dump('hc-' . $key);
        }

        $users = User::whereIn('register_as',['gc_lc','gc_hc'])->where('gc_status', 'approved')->get();
        foreach ($users as $key => $user) {
            if (isset($user->getFirstMedia('gc_profile_image')->id)) {
                $path = $user->getFirstMedia('gc_profile_image')->id . '/' . $user->getFirstMedia('gc_profile_image')->file_name;
                $user->update(['profile_image' => $path]);
            }
            \dump('user-' . $key);
        }

        dd('success');
    }
}
