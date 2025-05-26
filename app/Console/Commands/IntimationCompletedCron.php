<?php

namespace App\Console\Commands;

use App\Application;
use App\LowerCourt;
use Carbon\Carbon;
use Illuminate\Console\Command;

class IntimationCompletedCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'intimation:completed';

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
        echo "Loading...";

        $applications = Application::where('application_type', 6)->where('is_intimation_completed', 0)->get();
        echo "\n" . count($applications) . " of these application have to be checked  \n and update according to the parameters and conditions";
        foreach ($applications as $application) {
            $response = validateIntimationApplication($application,1);
            if(isset($response['status'])){
                echo "\n".$response['status'];
            }
            echo "\napplication served ccontaining id = " . $application->id;
        }
        echo "\nSuccess ... :)";
        return 1;

    }
}
