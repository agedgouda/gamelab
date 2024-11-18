<div>

    @if($user->portrait)
        <img src="{{ $user->portrait }}" class="block h-24 w-24"/>
    @else
        <x-application-logo class="block h-9 fill-current text-gray-800" />
    @endif
    {{ $user->name }} {{ $user->email }}


    @if($user->id != auth()->id() )
        @if(!auth()->user()->friends->contains($user->id))
            <div class="mt-3">
                <x-danger-button wire:click="addFriend({{ $user->id }})" class="mt-2">
                    Add to Friends List
                </x-danger-button>
            </div>
        @endif
    @endif

    <div class="font-bold mt-3">Favorite Games</div>
        @if(!$user->games || $user->games && count($user->games) == 0)
        No games chosen yet
        @else
            @foreach($user->games as $game)
                <div 
                    class="text-sm w-96 hover:bg-gray-100 cursor-pointer {{ $loop->odd ? 'bg-gray-200' : '' }}"
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
                    class="text-sm w-96 hover:bg-gray-100 cursor-pointer {{ $loop->odd ? 'bg-gray-200' : '' }}"
                    @click="window.location.href = '{{ route('directory-entry', ['userId' => $friend->id]) }}' "  
                >
                    {{ $friend->name }}
                </div>
            @endforeach
        @endif
        
        @if(isset($user->events) && count($user->events) > 0)
        <div class="font-bold mt-3">Games Scheduled</div>
            @foreach($user->events as $event)
                <div 
                    class="text-sm w-96 hover:bg-gray-100 cursor-pointer {{ $loop->odd ? 'bg-gray-200' : '' }}"
                    @click="window.location.href = '{{ route('view-event', ['eventId' => $event->id]) }}' "  
                >
                    {{ $event->game->name }}
                </div>
            @endforeach
        @endif
</div>
