<div>

    @if($changeImage)
        <livewire:image-upload :subdirectory="'users/'.auth()->id()"/>
    @else
        @if(auth()->user()->portrait)
            <img src="{{auth()->user()->portrait}}" class="block w-28"/>
        @else
            <x-application-logo class="block h-9 w-14 fill-current text-gray-800" />
        @endif
    @endif
    <x-danger-button 
        type="button" 
        wire:click="toggleUpload" 
        class="align-middle h-2 ml-2"
        >
        {{ $changeImage ? __('Cancel') : __('Change') }}
    </x-danger-button>

    <div>
    {{auth()->user()->name}}
    </div>

</div>
