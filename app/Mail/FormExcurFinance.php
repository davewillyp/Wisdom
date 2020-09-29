<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormExcurFinance extends Mailable
{
    use Queueable, SerializesModels;
    public $form;
    public $expenses;
    public $expense;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->form = $data['form'];
        $this->expense = $data['expense'];
        $this->expenses = $data['expenses'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('staff.forms.excur.emails.finance')
        ->subject('Excursion notification');
    }
}
