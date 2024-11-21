<div>
    <x-primary-button class="pl-5 mb-2">
        <a href="/events">View all upcoming games</a>
    </x-primary-button>

    <div class="font-bold text-center flex flex-col text-3xl items-center">
        <img src="{{ $event->game->thumbnail }}" class="object-cover mb-2" />
        {{ $event->game->name }}
    </div>

    <div>
        <span class="font-bold">Where:</span> {{ $event->location }}
    </div>    
    <div>
        <span class="font-bold">Description:</span> {{ $event->description }}
    </div>    
    <div>
        <span class="font-bold">Principal Investigator:</span> {{ $event->user->name }}
    </div>
    <div>
        <div class="font-bold mb-2">
            Choose your availability from the dates below
        </div>
            @if(!auth()->id())
                <div class="text-sm">You must be logged in to RSVP. Click <a class="text-yellow-600 hover:text-yellow-700 cursor-pointer" href="{{route('login')}}">here to login</a>. Not registered? <a class="text-yellow-600 hover:text-yellow-700 cursor-pointer" href="{{route('register')}}">Click here</a>, it's free!<div>
            @else
                @if (!$event->proposedDates->some(function ($proposedDate)  {
                    return $proposedDate->availabilities->contains('user_id', auth()->id());
                })) 
                @endif
                <div class="text-sm">Click the thumbs up for each time you can attend, thumbs down for each day you can't.</div>
            @endif
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
        <div class="grid grid-cols-3 mb-2 pt-3 pb-3  {{ $loop->odd ? 'bg-green-100' : '' }}">
            
            @php
                // Get the availability for the current user
                $currentUserAvailability = $date->availabilities->where('user_id', auth()->id())->first();
            @endphp
            
            <div class="flex items-center ml-5">
                {{ \Carbon\Carbon::parse($date->date_time)->format('m/d/Y') }} at {{ \Carbon\Carbon::parse($date->date_time)->format('h:i A') }}
            </div>
            <div x-data="{ selected: @if($currentUserAvailability && !is_null($currentUserAvailability->is_available)) {{ $currentUserAvailability->is_available }} @else null @endif, hover: null }" class="ml-5">
                <!-- Container for SVGs and centered text -->
                <div class="flex flex-col items-center">
                    <!-- SVGs side by side -->
                    <div class="flex space-x-2">
                        <!-- First SVG -->
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke-width="1.5" 
                            stroke="currentColor" 
                            class="h-6"
                            :class="{
                                'text-teal-700': selected === 1 || hover === 1,
                                'text-gray-500': selected === 0 || hover === null && selected === null,
                            }"
                            @click="selected = 1; hover = 1; $wire.enterAvailability(1, {{ $date->id }})"
                            @mouseover="hover = 1" 
                            @mouseleave="hover = null"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                        </svg>
                        <!-- Second SVG -->
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke-width="1.5" 
                            stroke="currentColor" 
                            class="h-6"
                            :class="{
                                'text-red-600': selected === 0 || hover === 0,
                                'text-gray-500': selected === 1 || hover === null && selected === null,
                            }"
                            @click="selected = 0; hover = 0; $wire.enterAvailability(0, {{ $date->id }})"
                            @mouseover="hover = 0" 
                            @mouseleave="hover = null"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.498 15.25H4.372c-1.026 0-1.945-.694-2.054-1.715a12.137 12.137 0 0 1-.068-1.285c0-2.848.992-5.464 2.649-7.521C5.287 4.247 5.886 4 6.504 4h4.016a4.5 4.5 0 0 1 1.423.23l3.114 1.04a4.5 4.5 0 0 0 1.423.23h1.294M7.498 15.25c.618 0 .991.724.725 1.282A7.471 7.471 0 0 0 7.5 19.75 2.25 2.25 0 0 0 9.75 22a.75.75 0 0 0 .75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 0 0 2.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384m-10.253 1.5H9.7m8.075-9.75c.01.05.027.1.05.148.593 1.2.925 2.55.925 3.977 0 1.487-.36 2.89-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398-.306.774-1.086 1.227-1.918 1.227h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 0 0 .303-.54" />
                        </svg>
                    </div>
                    <!-- Centered Span -->
                    @if ($currentUserAvailability)
                    <span class="text-center mt-2">{{$currentUserAvailability->is_available ? 'You are available' : 'You are not available' }}</span>
                    @endif
                </div>
            </div>
            
            <div class="flex text-center items-center justify-center  ml-5">
                @if(!$event->selectedDate)
                    @if($event->user->id == auth()->id())
                    <x-secondary-button wire:click="setEventDate({{ $date->id }})" class="pl-5" wire:loading.remove>
                        {{ __('Make Game Day') }}
                    </x-secondary-button>

                    <!-- Show "Processing..." text when the method is executing -->
                    <span wire:loading class="pl-5 mb-2">Processing...</span>
                    @else
                        Waiting for {{$event->user->name}} to finalize date
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
            @if($event->user->id === auth()->id())
            <x-danger-button class="mt-2 mr-2">
                <a href="{{ route('edit-event', ['eventId' => $event->id]) }}">Edit</a>
            </x-danger-button>
            @endif
        </div>

        <div class="flex mb-5">
            <div class="hidden space-x-8 sm:-my-px sm:flex">
                <x-nav-link @click="$wire.set('activeTab', 'rsvp')" :active="$activeTab == 'rsvp'" class="cursor-pointer" wire:navigate>
                    {{ __('RSVP') }}
                </x-nav-link>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
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

    @if($activeTab == 'rsvp')

    <table class="w-full bg-slate-100 border-collapse border border-gray-300">
        <thead class="bg-green-100 text-yellow-900">
            <tr>
                <th class="font-semibold border border-gray-300 p-2">Date</th>
                <th class="font-semibold border border-gray-300 p-2">Available</th>
                <th class="font-semibold border border-gray-300 p-2">Not Available</th>
                @if($event->user->id == auth()->id())
                <th class="font-semibold border border-gray-300 p-2"></th>
                @endif
            </tr>
        </thead>
        <tbody>
        @foreach ($event->proposedDates as $date)
            @php
                $availabilitiesForDate = collect($userAvailabilities)->where('date_id', $date->id);
                [$available, $notAvailable] = collect($availabilitiesForDate)
                    ->partition(fn($availability) => $availability['is_available'] == 1)
                    ->map(fn($group) => $group->values());

            @endphp
            <tr>
                <td class="border border-gray-300 p-2 text-center align-middle">
                    {{ \Carbon\Carbon::parse($date->date_time)->format('m/d/Y') }} at {{ \Carbon\Carbon::parse($date->date_time)->format('h:i A') }}
                </td>

                <td class="border border-gray-300 p-2 text-center align-middle">
                    @foreach($available as $player)
                        <div>{{$player["user_name"]}}</div>
                    @endforeach
                </td>

                <td class="border border-gray-300 p-2 text-center align-middle">
                    @foreach($notAvailable as $player)
                        <div>{{$player["user_name"]}}</div>
                    @endforeach
                </td>

                @if($event->user->id == auth()->id())
                <td class="border border-gray-300 p-2 text-center align-middle">
                    <x-secondary-button wire:click="setEventDate({{ $date->id }})" class="pl-5" wire:loading.remove>
                        {{ __('Make Game Day') }}
                    </x-secondary-button>

                    <!-- Show "Processing..." text when the method is executing -->
                    <span wire:loading class="pl-5 mb-2">Processing...</span>
                </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    @elseif($activeTab == 'invitees')
        <livewire:events.invite-players :eventId="$event->id" />   
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
