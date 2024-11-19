<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            @switch(true)
                @case(request()->routeIs('welcome-event') || request()->routeIs('events') )
                    Upcoming Games
                    @break
                @case(request()->routeIs('view-event'))
                    Game Details
                    @break
                @case(request()->routeIs('edit-event'))
                    Edit Upcoming Game
                    @break
            @endswitch
        </h2>
    </x-slot>

    <div>
       
        @switch(true)
            @case(request()->routeIs('welcome-event') || request()->routeIs('events') )
                @livewire('events.list-events')
                @break
            @case(request()->routeIs('view-event'))
                @livewire('events.view-event',['eventId' => $eventId])
                @break
            @case(request()->routeIs('edit-event'))
                @livewire('events.manage-event',['eventId' => $eventId])
                @break
            @case(request()->routeIs('schedule'))
                @livewire('events.manage-event')
                @break
        @endswitch
    </div>
</x-app-layout>
