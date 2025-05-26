<?php

namespace App\Console\Commands;

use App\GcLowerCourt;
use App\LowerCourt;
use Illuminate\Console\Command;

class update_cnic_format_command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnic_format:update';

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
        $gc_lower_court = GcLowerCourt::get();
        foreach ($gc_lower_court as $key => $gc_lower_court) {
            $cnic = str_replace(' ', '', $gc_lower_court->cnic_no);
            $cnic = str_replace('-', '', $cnic);
            $format_cnic = substr($cnic, 0, 5) . '-' . substr($cnic, 5, 7) . '-' . substr($cnic, 12);

            $gc_lower_court->update([
                'cnic_no' => $format_cnic
            ]);

            dump('cnic updated ... ' . $key);
        }
    }
}
