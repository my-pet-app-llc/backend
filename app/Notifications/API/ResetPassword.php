<?php

namespace App\Notifications\API;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    private $token;

    private $email;

    /**
     * Create a new notification instance.
     *
     * @param $token
     * @param $email
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Reset Password')
                    ->line('You are trying to change your password.')
                    ->action('Your link for activation new password', route('api.reset.password', ['token' => $this->token, 'email' => $this->email]));
    }
}
