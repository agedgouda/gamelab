<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Directory') }}
        </h2>
    </x-slot>

    <div>
        @if(request()->routeIs('directory'))
            <livewire:directory.user-list />
        @elseif(request()->routeIs('directory-entry'))
            <livewire:directory.user-details :userId="$userId" />
        @endif
    </div>
</x-app-layout>
