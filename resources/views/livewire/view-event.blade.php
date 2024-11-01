<div>
    <x-primary-button class="pl-5 mb-2">
        <a href="/events">View all upcoming games</a>
    </x-primary-button>
    <div>
        <span class="font-bold">Title:</span> {{ $event->title }}
    </div>   
    <div>
        <span class="font-bold">Location:</span> {{ $event->location }}
    </div>    
    <div>
        <span class="font-bold">Game:</span> {{ $event->game->name }}
    </div>
    <div>
        <span class="font-bold">Principal Investigator:</span> {{ $event->user->name }}
    </div>
    <div>
        <div class="font-bold">Proposed Times</div>
        @php
            if ($event->date_selected_id) {
                // Filter to get only the selected date if it exists
                $datesToDisplay = $event->proposedDates->where('id', $event->date_selected_id);
                
            } else {
                // Otherwise, get all dates
                $datesToDisplay = $event->proposedDates;
            }
        @endphp
        @foreach($datesToDisplay as $date)
        <div class="grid grid-cols-3 mb-2 pt-3 pb-3  {{ $loop->odd ? 'bg-gray-200' : '' }}">
            
            @php
                // Get the availability for the current user
                $currentUserAvailability = $date->availabilities->where('user_id', auth()->id())->first();
                $availabilitiesForDate = collect($userAvailabilities)->where('date_id', $date->id);
                [$available, $notAvailable] = collect($availabilitiesForDate)->partition(fn($availability) => $availability['is_available'] == 1);
            @endphp
            
            <div class="flex items-center ml-5">
                {{ \Carbon\Carbon::parse($date->date_time)->format('m/d/Y') }} at {{ \Carbon\Carbon::parse($date->date_time)->format('h:i A') }}
            </div>
            <div x-data="{ selected: @if($currentUserAvailability && !is_null($currentUserAvailability->is_available)) {{ $currentUserAvailability->is_available }} @else null @endif, hover: null }" class="flex space-x-2 flex items-center ml-5">
                <!-- First SVG -->
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="h-6"
                    :class="{
                        'text-green-500': selected === 1 || hover === 1,
                        'text-gray-500': selected === 0 || hover === null && selected === null,
                    }"
                    @click="selected = 1; hover = 1; $wire.enterAvailability(1, {{ $date->id }})"
                    @mouseover="hover = 1" 
                    @mouseleave="hover = null"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                </svg>
                    @if ($available && count($available))
                        {{ $available->pluck('user_name')->implode(', ') }}
                    @else
                        No players yet
                    @endif
                <!-- Second SVG -->
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor" 
                    class="h-6"
                    :class="{
                        'text-red-500': selected === 0 || hover === 0,
                        'text-gray-500': selected === 1 || hover === null && selected === null,
                    }"
                    @click="selected = 0; hover = 0; $wire.enterAvailability(0, {{ $date->id }})"
                    @mouseover="hover = 0" 
                    @mouseleave="hover = null"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.498 15.25H4.372c-1.026 0-1.945-.694-2.054-1.715a12.137 12.137 0 0 1-.068-1.285c0-2.848.992-5.464 2.649-7.521C5.287 4.247 5.886 4 6.504 4h4.016a4.5 4.5 0 0 1 1.423.23l3.114 1.04a4.5 4.5 0 0 0 1.423.23h1.294M7.498 15.25c.618 0 .991.724.725 1.282A7.471 7.471 0 0 0 7.5 19.75 2.25 2.25 0 0 0 9.75 22a.75.75 0 0 0 .75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 0 0 2.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384m-10.253 1.5H9.7m8.075-9.75c.01.05.027.1.05.148.593 1.2.925 2.55.925 3.977 0 1.487-.36 2.89-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398-.306.774-1.086 1.227-1.918 1.227h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 0 0 .303-.54" />
                </svg>
                    @if ($notAvailable && count($notAvailable))
                        {{ $notAvailable->pluck('user_name')->implode(', ') }}
                    @endif
            </div>
            <div class="flex items-center ml-5">
                @if(!$event->selectedDate)
                    @if($event->user->id == auth()->id())
                    <x-secondary-button wire:click="setEventDate({{ $date->id }})" class="pl-5" wire:loading.remove>
                        {{ __('Make Game Day') }}
                    </x-secondary-button>

                    <!-- Show "Processing..." text when the method is executing -->
                    <span wire:loading class="pl-5 mb-2">Processing...</span>
                    @endif
                @elseif($event->selectedDate  && $event->selectedDate->id == $date->id)
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="green" class="h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                Game Day
                @endif
            </div>
        

        </div> 
        @endforeach
        <div class="flex mb-5 mt-5 justify-end">
            <x-danger-button class="mt-2">
                <a href="/events/edit/{{$event->id}}">Edit</a>
            </x-danger-button>
        </div>

        <div class="flex mb-5">
            <div class="hidden space-x-8 sm:-my-px sm:flex">
                <x-nav-link @click="$wire.set('activeTab', 'invitees')" :active="$activeTab == 'invitees'" class="cursor-pointer" wire:navigate>
                    {{ __('Invitees') }}
                </x-nav-link>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link @click="$wire.set('activeTab', 'game-details')" :active="$activeTab=='game-details'" class="cursor-pointer" wire:navigate>
                    {{ __('Game Details') }}
                </x-nav-link>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link @click="$wire.set('activeTab', 'posts')" :active="$activeTab == 'posts'" class="cursor-pointer" wire:navigate>
                    {{ __('Posts') }}
                </x-nav-link>
            </div>
        </div>        
    </div>  

    @if($activeTab == 'invitees')
        <livewire:invite-players :eventId="$event->id" />   
    @elseif($activeTab == 'posts')
        <livewire:post-component :postableId="$event->id" :postableType="'event'" />
    @elseif($activeTab == 'game-details')  
    
    <div class="grid grid-cols-2 mb-2">
        <div>
            <img src="{{ $bggGameData['thumbnail'] }}" alt="{{ $bggGameData['name'][0] }} Thumbnail" class="w-32 h-auto mt-2">
        </div>
        <div>
        {{ $bggGameData['minplayers'] }} - {{ $bggGameData['maxplayers'] }} Players<br/>
        Best: {{ $bggGameData['suggested_players'] }}<br/>
        {{ $bggGameData['type'] }}
        </div>
        @livewire('poll-component', ['pollableType' => 'game', 'pollableId' => $event->game->id])

    </div>
    
    <div>
        {!! $bggGameData['description'] !!}
    </div>
    @endif
</div>
