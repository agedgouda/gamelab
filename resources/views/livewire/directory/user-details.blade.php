<div>
    @if($user->portrait)
        <img src="{{ $user->portrait }}" class="block h-24 w-24"/>
    @else
        <x-application-logo class="block h-9 fill-current text-gray-800" />
    @endif
    {{ $user->name }}
    <div><span class="font-bold">Email:</span> <a href="mailto:{{ $user->email }}" class="text-yellow-900 cursor-pointer hover:text-yellow-400">{{ $user->email }}</a><div>


    @if($user->id != auth()->id() )
        <div>
            <x-danger-button 
                type="button" 
                wire:click="{{ auth()->user()->friends->contains($user->id) ? 'removeFriend(' . $user->id . ')' : 'addFriend(' . $user->id . ')' }}"
                class="align-middle h-2 ml-2">
                {{ auth()->user()->friends->contains($user->id) ? __('Remove from Friends List') :  __('Add to Friends List')  }}
            </x-danger-button>
        </div>
    @endif

    <div class="font-bold mt-3">Favorite Games</div>
        @if(!$user->games || $user->games && count($user->games) == 0)
        No games chosen yet
        @else
            @foreach($user->games as $game)
                <div 
                    class="text-sm w-96 hover:bg-teal-700 text-yellow-900 cursor-pointer hover:text-yellow-400 {{ $loop->odd ? 'bg-green-100' : '' }}"
                    @click="window.location.href = '{{ route('game-details', ['bggId' => $game->bgg_id]) }}' "  
                >
                    {{ $game->name }}
                </div>
            @endforeach
        @endif
        
        @if(isset($user->friends) && count($user->friends) > 0)
        <div class="font-bold mt-3">Friends</div>
            @foreach($user->friends as $friend)
                <div 
                    class="text-sm w-96 hover:bg-teal-700 text-yellow-900 cursor-pointer hover:text-yellow-400 {{ $loop->odd ? 'bg-green-100' : '' }}"
                    @click="window.location.href = '{{ route('directory-entry', ['userId' => $friend->id]) }}' "  
                >
                    {{ $friend->name }}
                </div>
            @endforeach
        @endif
        
        @if(isset($events) )
        <div class="font-bold mt-3">Games Scheduled</div>
            @foreach($events as $event)
                <div 
                    class="text-sm w-96 hover:bg-teal-700 text-yellow-900 cursor-pointer hover:text-yellow-400 {{ $loop->odd ? 'bg-green-100' : '' }}"
                    @click="window.location.href = '{{ route('view-event', ['eventId' => $event->id]) }}' "  
                >
                    {{ $event->game->name }} at {{ $event->location }}
                </div>
            @endforeach

            <div class="mt-1 w-1/2">
                {{ $events->links() }}
            </div>
        @endif

        <div>
            <x-secondary-button class="mt-3 flex items-center" @click="window.location='{{ route('directory') }}'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>                      
                Back
            </x-secondary-button>
        </div>

</div>
