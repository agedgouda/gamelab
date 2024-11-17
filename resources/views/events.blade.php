<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
                    @endswitch
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
