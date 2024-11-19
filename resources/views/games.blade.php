<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Manage Games') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
            </div>
        </div>
    </div>

</x-app-layout>
