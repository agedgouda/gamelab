<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Manage Games') }}
        </h2>
    </x-slot>

    <div>
        @switch(true)
            @case(request()->routeIs('games'))
                @livewire('game.create-game')
                @livewire('game.list-games')
                @break
            @case(request()->routeIs('game-details'))
                @livewire('game.game-details',['bggId' => $bggId])
                @break
        @endswitch
    </div>

</x-app-layout>
