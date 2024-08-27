<?php

namespace App\Livewire;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public $language;

    public function mount()
    {
        $this->language = auth()->user()->settings['language'] ?? 'en';
        App::setLocale($this->language);
    }

    public function switchLanguage($locale)
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
            session()->put('locale', $locale);
            Notification::make()
                ->title(__('filament-panels::translations.language_changed'))
                ->success()
                ->send();

            return $this->js('window.location.reload()');
        }else{
            Notification::make()
                ->title(__('filament-panels::translations.language_did_not_change'))
                ->warning()
                ->send();
        }

    }
    public function render()
    {
        return view('livewire.language-switcher',[
            'languages' => [
                'en' => 'English',
                'de' => 'Deutsch',
                'tr' => 'Türkce',
            ]
        ]);
    }
}
