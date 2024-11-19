<div>
    <div class="mx-auto">
        @if($changeImage)
            <div class="mb-3">
                <livewire:image-upload :subdirectory="'users/'.auth()->id()"/>
            </div>
        @else
            @if(auth()->user()->portrait)
                <img src="{{auth()->user()->portrait}}" class="block w-28"/>
            @else
                <x-application-logo class="block h-9 w-14 fill-current text-gray-800" />
            @endif
        @endif
        <x-primary-button 
            type="button" 
            wire:click="toggleUpload" 
            class="align-middle h-2 ml-2 mb-3 mt-1"
        >
            {{ $changeImage ? __('Cancel') : __('Change') }}
        </x-primary-button>
    </div>
    <div>
        {{auth()->user()->name}}
    </div>

</div>

