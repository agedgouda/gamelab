<div>

    <div class="mb-4 w-96">
        <input type="text" 
               wire:model.live="search" 
               class="form-input w-full" 
               placeholder="Search games by name..." />
    </div>
    <table class="w-full bg-slate-100 border-collapse border border-gray-300">
        <thead class="bg-green-100 text-yellow-900">
            <tr>
                <th class="font-semibold border border-gray-300 p-2 text-left">Name</th>
                <th class="font-semibold border border-gray-300 p-2 text-center">Boardgamegeek Rank</th>
                <th class="font-semibold border border-gray-300 p-2 text-center">Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($games as $game)
            
            <tr class="hover:bg-teal-700 text-yellow-900 cursor-pointer hover:text-yellow-400 font-spacemono "
            onclick="window.location='{{ route('game-details', ['bggId' => $game->bgg_id]) }}'"
            >
                <td class="border border-gray-300 p-2">
                    {{ $game->name }}
                </td>
                <td class="border border-gray-300 p-2 text-center">
                    {{ $game->rank }}
                </td>
                <td class="border border-gray-300 p-2 text-center">
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
                        {{ implode(', ', $categories) }}
                    @else
                        No categories available
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    

    <div class="mt-4">
        {{ $games->links() }}
    </div>

</div>
