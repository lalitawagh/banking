<?php

namespace Kanexy\Banking\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMoneyLocalCreditAlertEmail extends Mailable
{
    use Queueable, SerializesModels;


    public $user;
    public $transaction;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $transaction)
    {
        $this->user = $user;
        $this->transaction = $transaction;
    }


    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Confirmation of Credit Transaction',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'banking::banking.emails.sendMoneyLocalCreditAlert',
            with : [
                'user'=>$this->user,
                'transaction'=>$this->transaction,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
