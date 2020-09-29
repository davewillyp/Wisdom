<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormExcurBookings extends Mailable
{
    use Queueable, SerializesModels;
    public $logistics;
    public $form;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->logistics = $data['logistics'];
        $this->form = $data['form'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('staff.forms.pd.emails.booking')
        ->subject('Excursion notification');
    }
}
