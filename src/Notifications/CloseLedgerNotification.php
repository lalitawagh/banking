<?php

namespace Kanexy\Banking\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Kanexy\Cms\Setting\Models\Setting;

class CloseLedgerNotification extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $senderMail = Setting::getValue('sender_mail',[]);

        return (new MailMessage)->from($senderMail, config('mail.from.name'))
            ->line($notifiable->full_name)
            ->line('You are having balance in your account, cannot approve your request')
            ->line('Please transfer your balance and request again')
            ->action('Login', url('/'))
            ->line('Thank you !');
    }
}
