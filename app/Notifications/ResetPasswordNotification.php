<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token)
    {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Passwort zurücksetzen'))
            ->greeting(Lang::get('Hallo') . " {$notifiable->first_name},")
            ->line(Lang::get('Du erhältst diese E-Mail, weil wir eine Anfrage zum Zurücksetzen deines Passworts erhalten haben.'))
            ->action(Lang::get('Passwort zurücksetzen'), $this->resetUrl($notifiable))
            ->line(Lang::get('Dieser Link zum Zurücksetzen des Passworts läuft in :count Minuten ab.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('Wenn du keine Passwortzurücksetzung beantragt hast, sind keine weiteren Schritte erforderlich.'))
            ->line(Lang::get('Viele Grüße'))
            ->salutation(Lang::get('Entwicklungsabteilung'))



            ;
    }

    protected function resetUrl(mixed $notifiable): string
    {
        return Filament::getResetPasswordUrl($this->token, $notifiable);
    }

}
