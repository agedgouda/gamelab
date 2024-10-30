<div>
    <div class="relative">
    <form wire:submit="{{ $isEditMode ? 'updateEvent' : 'createEvent' }}"> 
        <div class="grid grid-cols-4">    
            <div class="mt-4 mb-5">
                <x-input-label for="title" :value="__('Name')" />
                <div class="flex items-center mt-1 mr-5">
                    <x-text-input wire:model="title" id="title" class="w-full" type="text" name="title"/>
                </div>
                <x-input-error :messages="$errors->get('form.title')" class="mt-2" />
            </div>
            <div class="mt-4 mb-5 col-span-3">
                <x-input-label for="location" :value="__('Location')" />
                <div class="flex items-center mt-1 mr-5">
                    <x-text-input wire:model="location" id="location" class="w-full" type="text" name="location"/>
                </div>
                <x-input-error :messages="$errors->get('form.location')" class="mt-2" />
            </div>
        </div>

        <div>
            <x-input-label for="description" :value="__('Description')" />
            <div class="flex items-center mt-1 mr-5">
                <x-text-input wire:model="description" id="description" class="w-full" type="text" name="description"/>
            </div>
            <x-input-error :messages="$errors->get('form.description')" class="mt-2" />
        </div>
        
        @if(!$isEditMode)
            @if(!$game)
            <div class="mt-4 mb-5">
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Search for a game..."
                    class="block w-full p-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
                />

                @if(count($games) > 0)
                    <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1">
                        @foreach($games as $game)
                            <li
                                wire:click="selectGame({{ $game->id }})"
                                class="cursor-pointer p-2 hover:bg-blue-100"
                            >
                                {{ $game->name }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            @endif
        @endif
        
        <p class="mt-4 flex items-center">
            @if($game)
                Game: {{ $game->name }}
                <x-danger-button type="button" wire:click="removeGame()"  class="align-middle h-2 ml-2">
                    {{ __('Change') }}
                </x-danger-button> 
            @endif
        </p>

        <!-- Add Date and Time Inputs -->
        <div class="mt-5">
            <livewire:calendar/>   
        </div>
        <!--
        <div class="grid grid-cols-3">
            <div class="mb-5">
                <x-input-label for="date" :value="__('Date')" />
                <div class="flex items-center mt-1 mr-5">
                    <x-text-input wire:model="date" id="date" class="w-48" type="date" name="date"/>
                </div>
            </div>

            <div class="mb-5">
                <x-input-label for="time" :value="__('Time')" />
                <div class="flex items-center mt-1 mr-5">
                    <x-text-input wire:model="time" id="time" class="w-48" type="time" name="time"/>
                </div>
            </div>

            <div class="mb-5 mt-7">
                <x-secondary-button wire:click="addDateTime" class="align-middle">
                    {{ __('Add Date') }}
                </x-secondary-button>
            </div>
        </div>
-->

        <!-- Display Added Dates and Times -->

        @foreach($dateTimes as $index => $dateTime)
            <div class="grid grid-cols-5 mt-3">
                <div class="mb-5">
                    <x-input-label :value="__('Date')" />
                    <div class="flex items-center mt-1 mr-5">
                        
                        <x-text-input value="{{ $dateTime['date'] }}" class="w-48" type="date" readonly/>
                    </div>
                </div>

                <div class="mb-5">
                    <x-input-label :value="__('Time')" />
                    <div class="flex items-center mt-1 mr-5">
                        <x-text-input value="{{ $dateTime['time'] }}" class="w-48" type="time" readonly/>
                    </div>
                </div>

                <div class="mb-5 text-sm">
                    @if($isEditMode)
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="green" class="h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                        </svg>
                    
                        @foreach($dateTime['available'] as $available)
                            <a class="hover:text-gray-500 cursor-pointer"  href="mailto:{{ $available->user->email }}">{{ $available->user->name }}</a> <br>
                        @endforeach
                    @endif
                </div>

                <div class="mb-5 text-sm">
                    @if($isEditMode)
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.498 15.25H4.372c-1.026 0-1.945-.694-2.054-1.715a12.137 12.137 0 0 1-.068-1.285c0-2.848.992-5.464 2.649-7.521C5.287 4.247 5.886 4 6.504 4h4.016a4.5 4.5 0 0 1 1.423.23l3.114 1.04a4.5 4.5 0 0 0 1.423.23h1.294M7.498 15.25c.618 0 .991.724.725 1.282A7.471 7.471 0 0 0 7.5 19.75 2.25 2.25 0 0 0 9.75 22a.75.75 0 0 0 .75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 0 0 2.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384m-10.253 1.5H9.7m8.075-9.75c.01.05.027.1.05.148.593 1.2.925 2.55.925 3.977 0 1.487-.36 2.89-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398-.306.774-1.086 1.227-1.918 1.227h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 0 0 .303-.54" />
                        </svg>
                        
                        @foreach($dateTime['notAvailable'] as $notAvailable)
                            <a class="hover:text-gray-500 cursor-pointer" href="mailto:{{ $notAvailable->user->email }}">{{ $notAvailable->user->name }}</a> <br>
                        @endforeach
                    @endif
                </div>
                @if(!$isEditMode)
                <div class="mb-5 mt-7">
                    <x-danger-button wire:click="removeDateTime({{ $index }})" class="align-middle">
                        {{ __('Remove') }}
                    </x-danger-button> 
                </div>
                @endif
            
            </div>
        @endforeach
        <div class="flex justify-end space-x-4 mt-4">
            <x-secondary-button wire:click="resetForm">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ml-4 pl-5">
                {{ $isEditMode ? __('Update Event') : __('Add Event') }}
            </x-primary-button>
        </div>
    </form>
    <div class="mt-5 font-bold">
        Upcoming Games
    </div>
    <div class="grid grid-cols-8">
        <div class="font-semibold">
            Title
        </div>
        <div class="font-semibold">
            Location
        </div>
        <div class="font-semibold">
            Description
        </div>
        <div class="font-semibold">
            Game
        </div>
        <div class="font-semibold col-span-3">
            <div class="grid grid-cols-3">
                <div class="font-semibold">
                    Date
                </div>
                <div class="font-semibold flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="green" class="h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                    </svg>
                </div>
                <div class="font-semibold flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.498 15.25H4.372c-1.026 0-1.945-.694-2.054-1.715a12.137 12.137 0 0 1-.068-1.285c0-2.848.992-5.464 2.649-7.521C5.287 4.247 5.886 4 6.504 4h4.016a4.5 4.5 0 0 1 1.423.23l3.114 1.04a4.5 4.5 0 0 0 1.423.23h1.294M7.498 15.25c.618 0 .991.724.725 1.282A7.471 7.471 0 0 0 7.5 19.75 2.25 2.25 0 0 0 9.75 22a.75.75 0 0 0 .75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 0 0 2.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384m-10.253 1.5H9.7m8.075-9.75c.01.05.027.1.05.148.593 1.2.925 2.55.925 3.977 0 1.487-.36 2.89-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398-.306.774-1.086 1.227-1.918 1.227h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 0 0 .303-.54" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="font-semibold">
            
        </div>
        @foreach($events as $event)
        <div class="align-middle">
            {{ $event->title}}
        </div>
        <div class="align-middle">
            {{ $event->location}}
        </div>
        <div class="align-middle">
            {{ $event->description}}
        </div>
        <div class="align-middle mb-5">
            {{ $event->game->name}}
        </div>
        <div class="align-middle mb-5 col-span-3">

            @foreach($event->proposedDates as $date)
            <div class="grid grid-cols-3">
                <div>
                    {{ \Carbon\Carbon::parse($date->date_time)->format('h:i A') }} {{ \Carbon\Carbon::parse($date->date_time)->format('m/d/Y') }}
                </div>
                @php
                    $availabilitiesForDate = $date->availabilities;
                    [$available, $notAvailable] = collect($availabilitiesForDate)->partition(fn($availability) => $availability['is_available'] == 1);
                @endphp
                <div class="flex justify-center">
                    {{count($available)}} 
                </div>
                <div class="flex justify-center">
                    {{count($notAvailable)}}
                </div>
            </div>
            @endforeach
        </div>
        <div>
            <x-danger-button wire:click="edit({{ $event->id }})" class="!py-1">
                {{ __('Details') }}
            </x-danger-button>
        </div>
        @endforeach
    </div>

</div>
