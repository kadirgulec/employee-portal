<?php

namespace App\Livewire;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportJsEvaluation\HandlesJsEvaluation;

class LanguageSwitcher extends Component
{
    public $language;

    public function mount(): void
    {
        $this->language = auth()->user()->settings['language'] ?? config('app.locale');
        App::setLocale($this->language);
    }

    public function switchLanguage($locale): void
    {
        if($locale != $this->language) {
            //save the new language to user settings
            $user = auth()->user();
            $settings = $user->settings;
            $settings['language'] = $locale;
            $user->settings = $settings;
            $user->save();

            //set the local language
            App::setLocale($locale);
            Notification::make()
                ->title(__('filament-panels::translations.language_changed'))
                ->success()
                ->send();

            $this->js('window.location.reload()');
        }else{
            Notification::make()
                ->title(__('filament-panels::translations.language_did_not_change'))
                ->warning()
                ->send();
        }

    }
    public function render(): View
    {
        return view('livewire.language-switcher',[
            'languages' => [
                'en' => 'English',
                'de' => 'Deutsch',
                'tr' => 'Türkçe',
            ]
        ]);
    }
}
