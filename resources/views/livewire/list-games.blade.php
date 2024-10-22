<div>

    <div class="mb-4 w-96">
        <input type="text" 
               wire:model.live="search" 
               class="form-input w-full" 
               placeholder="Search games by name..." />
    </div>
        <div class="grid grid-cols-3 gap-0">
            <div class="font-semibold w-9/12">
                Name
            </div>
            <div class="font-semibold text-center">
                Boardgamegeek Rank
            </div>
            <div class="font-semibold text-center">
                Type
            </div>
            @foreach ($games as $game)
                <div>
                    <a href="games/{{ $game->bgg_id}}" class="underline">{{ $game->name }}</a> 
                </div>
                <div class="text-center">
                    {{ $game->rank }}
                </div>
                <div class="text-center">
                @php
                    $categories = [];

                    if($game->strategy_games_rank) {
                        $categories[] = 'Strategy ('.$game->strategy_games_rank.')';
                    }

                    if($game->childrens_games_rank) {
                        $categories[] = 'Children ('.$game->childrens_games_rank.')';
                    }

                    if($game->family_games_rank) {
                        $categories[] = 'Family ('.$game->family_games_rank.')';
                    }

                    if($game->party_games_rank) {
                        $categories[] = 'Party ('.$game->party_games_rank.')';
                    }

                    if($game->thematic_rank) {
                        $categories[] = 'Thematic ('.$game->thematic_rank.')';
                    }

                    if($game->wargames_rank) {
                        $categories[] = 'Wargame ('.$game->wargames_rank.')';
                    }
                @endphp

                @if (!empty($categories))
                    <div>{{ implode(', ', $categories) }}</div>
                @else
                    <div>No categories available</div>
                @endif
                
            </div>
            @endforeach
        </div>


    <div class="mt-4">
        {{ $games->links() }}
    </div>

</div>
