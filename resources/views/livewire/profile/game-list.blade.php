<div>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Favorite Games') }}
        </h2>
    </header>
    <div class="max-w-xl">
        <livewire:game.choose-game :userId="auth()->id()" />
    </div>
    @foreach($games as $game)
    <div wire:key="game-{{ $game->id }}" class="w-6/12 flex justify-between items-center">
        <span>{{ $game->name }}</span>
        <x-danger-button 
            type="button" 
            wire:click="removeGame('{{ $game->id }}')" 
            class="align-middle h-2 ml-2"
        >
            {{ __('Remove') }}
        </x-danger-button> 
    </div>
    @endforeach
</div>
