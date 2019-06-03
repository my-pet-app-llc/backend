<?php

namespace App\Notifications\API;

use App\Ticket;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReportUser extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    public $mail;

    /**
     * Create a new notification instance.
     *
     * @param Ticket $ticket
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->mail = (new MailMessage)->subject('You have been reported');
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
     * @param  User  $notifiable
     * @return MailMessage
     */
    public function toMail(User $notifiable)
    {
        return $this->mail->view('mail.report-user', [
                        'user' => $notifiable,
                        'ticket' => $this->ticket
                    ]);
    }
}
