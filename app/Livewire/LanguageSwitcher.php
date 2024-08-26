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
        $this->language = App::getLocale();
    }

    public function switchLanguage($locale)
    {
        if($locale != App::getLocale()) {
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
