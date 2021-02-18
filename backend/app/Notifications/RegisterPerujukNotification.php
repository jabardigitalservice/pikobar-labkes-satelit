<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterPerujukNotification extends Notification
{
    use Queueable;

    protected $password;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
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
        $appName = config('app.name');
        return (new MailMessage())
            ->subject("Undangan berpartisipasi - Pikobar")
            ->greeting('Halo!')
            ->line("Anda diundang untuk menjadi salah satu user fasyankes perujuk pada aplikasi $appName")
            ->line("Berikut detail informasi akun yang dapat digunakan saat login")
            ->line("Username: $notifiable->username")
            ->line("Password: $this->password")
            ->line("Jangan memberitahu akun ini kesiapapun yang tidak bertanggung jawab")
            ->action('Klik Untuk Login', config('app.url'))
            ->line('Terimakasih atas partisipasi anda!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }


}
