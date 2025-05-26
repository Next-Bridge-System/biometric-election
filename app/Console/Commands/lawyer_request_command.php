<?php

namespace App\Console\Commands;

use App\LawyerRequest;
use App\Payment;
use Illuminate\Console\Command;

class lawyer_request_command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lawyer_requests:update';

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
        $lawyer_requests = LawyerRequest::get();
        foreach ($lawyer_requests as $key => $lawyer_request) {
            dump('running .. ');
            $payment = Payment::where('lawyer_request_id', $lawyer_request->id)->first();
            if ($payment->payment_status == 1) {
                $lawyer_request->update([
                    'payment_status' => 'paid'
                ]);
            }
            if ($payment->voucher_file) {
                $lawyer_request->update([
                    'voucher_file' => 'attached'
                ]);
            }
        }
    }
}
