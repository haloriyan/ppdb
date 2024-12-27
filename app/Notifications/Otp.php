<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class Otp extends Notification
{
    use Queueable;

    public $code;
    public $student;

    /**
     * Create a new notification instance.
     */
    public function __construct($props)
    {
        $this->code = $props['code'];
        $this->student = $props['student'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject("Kode OTP PPDB - " . env('APP_NAME'))
                    ->greeting('Halo, ' . $this->student->name)
                    ->line("Berikut adalah kode OTP Anda")
                    ->line(
                        new HtmlString('<div style="font-size: 42px;font-weight: 700;margin-bottom: 40px;color: #2196f3;">' . $this->code . '</div>')
                    );
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
