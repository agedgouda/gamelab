<div class="mt-4 mb-5">
    <input
        type="text"
        wire:model.live="search"
        placeholder="Search for a games..."
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