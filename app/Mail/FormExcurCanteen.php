<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormExcurCanteen extends Mailable
{
    use Queueable, SerializesModels;
    public $count;
    public $form;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    
    public function __construct($data)
    {
        $this->count = $data['students'];
        $this->form = $data['form'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('staff.forms.excur.emails.canteen')
        ->subject('Excursion notification');
    }
}
