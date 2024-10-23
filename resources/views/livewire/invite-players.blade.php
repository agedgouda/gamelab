<div class="mb-5">
    <div class="font-bold">
            Invitees 
    </div>
    <div>
        @foreach($invitees as $invitee)
        <div class="grid grid-cols-3 {{ $loop->odd ? 'bg-gray-200' : '' }} py-4">
            <div class="flex items-center pl-3">
                @if($invitee->user)
                    <x-application-logo class="h-6 w-[30px] text-gray-800 " />
                @endif
                <span class="mr-2">{{ $invitee->name }}</span>
            </div>
            <div class="flex items-center pl-3">{{ $invitee->email }}</div>
            <div class="flex justify-center">{{ $invitee->message_status }}</div>
        </div>
        @endforeach
        <form wire:submit.prevent="sendInvite({{ $eventId }})">
            <div class="grid grid-cols-3 mb-2 {{ count($invitees) % 2 === 0 ? 'bg-gray-200' : '' }} py-4">
                <div class="flex flex-col items-start pl-3">
                    <input type="text" wire:model.defer="name" placeholder="Enter Name" class="border rounded px-2 py-1 w-full" />
                    @error('name') 
                        <span class="error text-red-500 mt-1 text-left w-full">{{ $message }}</span> 
                    @enderror
                </div>
                <div class="flex flex-col items-start pl-3">
                    <input type="email" wire:model.defer="email" placeholder="Enter Email" class="border rounded px-2 py-1 w-full" />
                    @error('email') 
                        <span class="error text-red-500 mt-1 text-left w-full">{{ $message }}</span> 
                    @enderror
                </div>
                <div class="flex justify-center">
                    <!-- Invite Error Message -->
                    @error('invite') 
                        <div class="error text-red-500">{{ $message }}</div>
                    @enderror
                    <x-primary-button class="px-4 py-2">
                        Send Invitation
                    </x-primary-button>
            </div>
        </form>
    </div>
</div>
