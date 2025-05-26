<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(isset($this->data['attachment'])){
            \Log::info('in-here');
            return $this->subject($this->data['subject'])->view('mails.general-mail')->attach($this->data['attachment'], [
                'as' => Carbon::now()->format('Ymd_His').'.pdf',
                'mime' => 'application/pdf',
            ]);
        }else{
            return $this->subject($this->data['subject'])->view('mails.general-mail');
        }
    }
}
