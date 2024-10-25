<x-mail::layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header url="https://www.aks-service.de/">
            <img class="w-7 h-auto sm:h-8"
                 src="https://www.aks-service.de/wp-content/uploads/2022/10/aks_logo_mit_system_und_medienhaus_20200114-300x151.png"
                 alt="aks logo">
        </x-mail::header>
    </x-slot:header>

    {!!html_entity_decode($slot) !!}


    <x-slot:footer>
        <x-mail::footer>
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        </x-mail::footer>
    </x-slot:footer>
</x-mail::layout>
