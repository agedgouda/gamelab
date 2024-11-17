<div>
    <div class="inline-block min-w-full overflow-hidden">
        <div class="w-full h-full flex p-5 flex-col text-center bg-sky-200">
            {{ \Carbon\Carbon::parse($weekday->day)->format('l, F jS') }}
        </div>
        @foreach ($times as $time)
    <div x-data class="w-full flex flex-row">
        <div
            class="w-full h-full"
            @click="$wire.addSelectedDateTime('{{ $weekday->day }} {{ $time }}')"
        >
            <div
                class="w-full h-full flex flex-col pl-2 border border-b-0 hover:bg-gray-200  {{ $this->getBackgroundClass($weekday->day, $time) }}"
                :class="bg-black bg-red-800"
            >
                {{ $time }}
            </div>
        </div>
    </div>    
@endforeach





    </div>
    <div  class="mb-5 text-center">
        <x-primary-button wire:click="close()">
            {{  __('Save Dates') }}
        </x-primary-button>
    </div>
</div>
