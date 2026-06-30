<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class UserWelcomeNotification extends Notification
{
    use Queueable;

    public string $adminName;

    public function __construct(string $adminName)
    {
        $this->adminName = $adminName;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        // Laravel ka built-in password reset token generate karo
        $token = Password::createToken($notifiable);

        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $notifiable->email,
        ], false));

        return (new MailMessage)
            ->subject('Welcome to '.config('app.name').' — Set Your Password')
            ->greeting('Assalam o Alaikum, '.$notifiable->name.'!')
            ->line('Your account has been created by admin **'.$this->adminName.'** on '.config('app.name').'.')
            ->line('**Username:** '.$notifiable->username)
            ->line('**Role:** '.ucfirst(str_replace('_', ' ', $notifiable->role)))
            ->action('Set Your Password', $resetUrl)
            ->line('This link will expire in **60 minutes**.')
            ->line('If you did not expect this email, please contact your system administrator.')
            ->salutation('— '.config('app.name').' Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
