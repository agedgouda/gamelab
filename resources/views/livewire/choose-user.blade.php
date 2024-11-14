<div class="mt-4 mb-5">

    <input
        type="text"
        wire:model.live="search"
        placeholder="Find a lab partner..."
        class="block w-full p-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
    />
    @if(count($users) > 0)
        <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 no-bullets">
            @foreach($users as $user)
                <li
                    wire:click="selectUser({{ $user->id }})"
                    class="cursor-pointer p-2 hover:bg-blue-100"
                >
                    {{ $user->name }}
                </li>
            @endforeach
        </ul>
    @endif

    

</div>