<?php

namespace App\Livewire;

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
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->to('/');
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
