<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Directory') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(request()->routeIs('directory'))
                        <livewire:directory.user-list />
                    @elseif(request()->routeIs('directory-entry'))
                        <livewire:directory.user-details :userId="$userId" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
