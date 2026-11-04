<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationCode extends Notification
{
    use Queueable;

    public function __construct(private readonly string $code) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Código de confirmação do primeiro acesso')
            ->greeting('Olá, '.$notifiable->name.'!')
            ->line('Use este código para confirmar seu e-mail e concluir o primeiro acesso:')
            ->line($this->code)
            ->line('O código expira em 10 minutos.');
    }
}
