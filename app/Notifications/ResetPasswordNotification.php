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
//            ->greeting(Lang::get('Hallo') . " {$notifiable->first_name},")
//            ->line(Lang::get('Du erhältst diese E-Mail, weil wir eine Anfrage zum Zurücksetzen deines Passworts erhalten haben.'))
//            ->action(Lang::get('Passwort zurücksetzen'), $this->resetUrl($notifiable))
//            ->line(Lang::get('Dieser Link zum Zurücksetzen des Passworts läuft in :count Minuten ab.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
//            ->line(Lang::get('Wenn du keine Passwortzurücksetzung beantragt hast, sind keine weiteren Schritte erforderlich.'))
//            ->line(Lang::get('Viele Grüße'))
//            ->salutation(Lang::get('Entwicklungsabteilung'))
            ->markdown('mail.invoice.paid',[
                'slot' => '
        <h2 class="text-gray-700 dark:text-gray-200">Hallo '.$notifiable->first_name.',</h2>
        <p class="mt-2 leading-loose text-gray-600 dark:text-gray-300">
            Du erhältst diese E-Mail, weil wir eine Anfrage zum Zurücksetzen deines Passworts erhalten haben.
        </p>
        <a class="button button-primary" target="_blank" rel="noopener"
           href="'.$this->resetUrl($notifiable).'">
            Passwort zurücksetzen
        </a>
        <p class="mt-2 leading-loose text-gray-600 dark:text-gray-300">
            Dieser Link zum Zurücksetzen des Passworts läuft in 60 Minuten ab.
        </p>
        <p class="mt-2 leading-loose text-gray-600 dark:text-gray-300">
            Wenn du keine Passwortzurücksetzung beantragt hast, sind keine weiteren Schritte erforderlich.
        </p>
        <p class="mt-8 text-gray-600 dark:text-gray-300">
            Viele Grüße, <br>
            Entwicklungsabteilung
        </p>
    ',
//                'first_name' => $notifiable->first_name,
//                'password_reset_link' => $this->resetUrl($notifiable),
            ])
//            ->with([
//                'subcopy' => 'Wenn du Probleme hast, den "Passwort zurücksetzen"-Button zu klicken, kopiere den folgenden Link und füge ihn in deinen Webbrowser ein:',
//            ])


            ;




    }

    protected function resetUrl(mixed $notifiable): string
    {
        return Filament::getResetPasswordUrl($this->token, $notifiable);
    }

}
