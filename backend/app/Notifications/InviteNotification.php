<?php

namespace App\Notifications;

use App\Enums\RoleEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteNotification extends Notification
{
    use Queueable;

    protected $notification_url;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification_url)
    {
        $this->notification_url = $notification_url;
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
        $additionalMessage = $this->messageRole($notifiable);
        $appName = config('app.name');
        return (new MailMessage())
            ->subject("Undangan berpartisipasi - Pikobar")
            ->greeting('Halo!')
            ->line("Anda diundang untuk menjadi salah satu admin $additionalMessage pada aplikasi $appName")
            ->action('Klik Untuk Daftar', $this->notification_url)
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

    private function messageRole($notifiable)
    {
        $additionalMessage = "";
        if ($notifiable->role_id == RoleEnum::DINKES()->getIndex()) {
            $additionalMessage = RoleEnum::DINKES()->getValue();
        }
        if ($notifiable->role_id == RoleEnum::LABORATORIUM()->getIndex()) {
            $additionalMessage = RoleEnum::LABORATORIUM()->getValue();
        }
        return $additionalMessage;
    }
}
