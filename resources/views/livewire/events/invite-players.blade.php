<div class="mb-5">
    <div>
        @php
            // Calculate the total number of items in the first loop
            $inviteeCount = $invitees->count();
        @endphp
        @error('invite') 
            <div class="error text-red-500 flex mb-3 justify-center">{{ $message }}</div>
        @enderror
        <div wire:loading wire:target="sendInvite" class="text-gray-500">Processing...</div>
        @foreach($invitees as $index => $invitee)
            <div class="grid grid-cols-3 {{ $loop->odd ? 'bg-green-100' : '' }} py-4">
                <div class="flex items-center pl-3">
                    @if( $invitee->user && $invitee->user->friendOf->contains('id', auth()->id()))
                        <x-application-logo class="h-6 w-[30px] text-gray-800 " />
                    @endif
                    <span class="mr-2">{{ $invitee->name }} </span>
                </div>
                <div class="flex items-center pl-3">{{ $invitee->email }}</div>
                @if( !$invitee->user ||  ($invitee->user && !$invitee->user->events->contains('id',$eventId)))
                    <div class="flex justify-center" wire:loading.remove wire:click="sendInvite({{ $eventId }}, '{{ $invitee->name }}', '{{ $invitee->email }}')">
                        @if($invitee->user && collect($invitee->user->availabilities)->pluck('proposed_date_id')->intersect(collect($event->proposedDates)->pluck('id'))->isNotEmpty()) 
                            User Responded
                        @else
                        <x-danger-button class="px-4 py-2">
                            Resend Invitation
                        </x-danger-button>
                        @endif
                    </div>
                @else
                    <div class="flex justify-center font-bold">Event Organizer</div>
                @endif
            </div>
        @endforeach

        @foreach($uninvitedFriends as $index => $friend)
            @php
                // Calculate the current loop index by adding the count of the first loop
                $currentIndex = $inviteeCount + $index;
            @endphp
            <div class="grid grid-cols-3 {{ $currentIndex % 2 == 0 ? 'bg-green-100' : '' }} py-4">
                <div class="flex items-center pl-3">
                    <x-application-logo class="h-6 w-[30px] text-gray-800 " />
                    <span class="mr-2">{{ $friend->name }}</span>
                </div>
                <div class="flex items-center pl-3">{{ $friend->email }}</div>
                <div class="flex justify-center" wire:loading.remove wire:click="sendInvite({{ $eventId }}, '{{ $friend->name }}', '{{ $friend->email }}')">
                    <x-danger-button class="px-4 py-2">
                        Send Invitation
                    </x-danger-button>
                </div>
            </div>
        @endforeach

        @if(auth()->check())
        <form wire:submit.prevent="sendInvite({{ $eventId }})">
            <div class="grid grid-cols-3 mb-2 {{ !isset($currentIndex) || ($currentIndex + 1) % 2 === 0 ? 'bg-gray-200' : '' }} py-4">
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
                    <div wire:loading.remove wire:target="sendInvite">
                        <x-primary-button class="px-4 py-2">
                            Send Invitation
                        </x-primary-button>
                    </div>
                </div>
            </div>

            <!-- Feedback messages -->
            <div class="mt-2">
                @if($emailSent === true)
                    <div class="text-green-500">Invitation Sent</div>
                @elseif($emailSent === false)
                    <div class="text-red-500">Email not sent. Please try again.</div>
                @endif
            </div>
        </form>
        @endif
    </div>
</div>
