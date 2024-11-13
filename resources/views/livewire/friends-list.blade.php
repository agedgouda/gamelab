<div>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Friends') }}
        </h2>
    </header>
    <div class="max-w-xl">
        <livewire:choose-user :userId="auth()->id()" />
    </div>

    @foreach($friends as $friend)
    <div wire:key="friend-{{ $friend->id }}" class="w-6/12 flex justify-between items-center">
        <span>{{ $friend->name }}</span>
        <x-danger-button 
            type="button" 
            wire:click="removeFriend('{{ $friend->id }}')" 
            class="align-middle h-2 ml-2"
        >
            {{ __('Remove') }}
        </x-danger-button> 
    </div>
    @endforeach
</div>
