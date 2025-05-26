<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Application;
use Storage;

class StorageCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:cron';

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
        // \Log::info("Storage Cron is working fine!");

        $application_last_item = Application::orderBy('id','desc')->first();
        $count = $application_last_item->id;

        echo "Loading ... ";

        for ($i=1; $i <= $count; $i++) {
            $application = Application::find($i);
            if (isset($application)) {
            } else {
                Storage::deleteDirectory('applications/uploads/'.$i);
                Storage::deleteDirectory('fingerprints/'.$i);

                $percentage = $i/$count * 100;
                // echo $percentage;
            }
        }

        echo "<<<<<<< SUCCESS >>>>>>>>";
    }
}
