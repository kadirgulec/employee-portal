<div x-data=" { open: false }" class="fi-dropdown fi-user-menu">
    <div @click="open = !open" class="fi-dropdown-trigger flex cursor-pointer"
         x-ref="button">
        <button class="shrink-0"><img src="{{__('filament-panels::translations.language')}}"
                                      class="fi-avatar object-cover object-center fi-circular rounded-full h-8 w-8 fi-user-avatar"
                                      alt="language"></button>
    </div>
    <div
        x-show="open"
        @click.away="open = false"
        x-anchor.bottom-start.offset.10 ="$refs.button"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="fi-dropdown-panel absolute z-10 w-32  divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 transition dark:divide-white/5 dark:bg-gray-900 dark:ring-white/10 !max-w-[14rem]">


        @foreach($languages as $code => $name)
            <button
                x-show="open"
                @click.away="open = false"
                wire:click="switchLanguage('{{ $code }}')"
                class="fi-dropdown-list-item flex w-full items-center gap-2 whitespace-nowrap rounded-md p-2 text-sm transition-colors duration-75 outline-none disabled:pointer-events-none disabled:opacity-70 hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5 fi-dropdown-list-item-color-gray fi-color-gray"
            >
                <span
                    class="fi-dropdown-list-item-label flex-1 truncate text-start text-gray-700 dark:text-gray-200">{{ $name }}</span>
            </button>
        @endforeach
    </div>
</div>
