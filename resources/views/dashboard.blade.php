<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @switch(true)
                        @case(request()->routeIs('games'))
                            @livewire('create-game')
                            @livewire('list-games')
                            @break

                        @case(request()->routeIs('schedule') || request()->routeIs('dashboard'))
                            @livewire('schedule-game')
                            @break

                        @case(request()->routeIs('users'))
                            @livewire('users')
                            @break
                        
                        @case(request()->routeIs('game-details'))
                            @livewire('game-details',['bggId' => $bggId])
                            @break

                        @default
                            <p>No matching route found.</p>
                    @endswitch
                </div>
            </div>
        </div>

 
    </div>

</x-app-layout>
