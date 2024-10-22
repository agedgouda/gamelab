<div>
    <x-primary-button class="pl-5 mb-2">
        <a href="javascript:void(0)" onclick="window.history.back()">Back</a>
    </x-primary-button>
    
    <div class="font-bold text-xl mb-2">{{ $game->name }}</div>

    <div class="grid grid-cols-2 mb-2">
        <div>
            <img src="{{ $bggGameData['thumbnail'] }}" alt="{{ $bggGameData['name'][0] }} Thumbnail" class="w-32 h-auto mt-2">
        </div>
        <div>
        {{ $bggGameData['minplayers'] }} - {{ $bggGameData['maxplayers'] }} Players<br/>
        Best: {{ $bggGameData['suggested_players'] }}<br/>
        {{ $bggGameData['type'] }}
        </div>
        @livewire('poll-component', ['pollableType' => 'game', 'pollableId' => $game->id])

    </div>
    
    <div>
        {!! $bggGameData['description'] !!}
    </div>
    
    @if($bggGameData['expansions'])
        <div class="font-bold"> Expansions </div>
        {!! $bggGameData['expansions'] !!}
    @endif

</div>
