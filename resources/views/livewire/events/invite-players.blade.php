<div class="mb-5">
    <div>

        @error('invite') 
            <div class="error text-red-500 flex mb-3 justify-center">{{ $message }}</div>
        @enderror
        <div wire:loading wire:target="sendInvite" class="text-gray-500">Processing...</div>
        @if(auth()->check())

        <form wire:submit.prevent="sendInvite({{ $eventId }})">
            <div class="grid grid-cols-3 mb-2 py-4">
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

        <table class="w-full bg-slate-100 border-collapse border border-gray-300">
            <thead class="bg-green-100 text-yellow-900">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">Invitee</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invitees as $index => $invitee)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">
                            <div class="flex items-center">
                                @if($invitee->user && $invitee->user->portrait)
                                <img src="{{ $invitee->user->portrait }}" class="block h-9 w-9 rounded-full mr-2"/>
                            @elseif($invitee->user && !$invitee->user->portrait)
                                <img src="/img/user.svg" class="block w-9 mr-2"/>
                            @endif
                            <span>{{ $invitee->name }}</span>
                            </div>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ $invitee->email }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            @if(!$invitee->user || ($invitee->user && !$invitee->user->events->contains('id', $eventId)))
                                @if($invitee->user && collect($invitee->user->availabilities)->pluck('proposed_date_id')->intersect(collect($event->proposedDates)->pluck('id'))->isNotEmpty())
                                    <span>User Responded</span>
                                @else
                                    <x-danger-button 
                                        class="px-4 py-2"
                                        wire:loading.remove 
                                        wire:click="sendInvite({{ $eventId }}, '{{ $invitee->name }}', '{{ $invitee->email }}')">
                                        Resend Invitation
                                    </x-danger-button>
                                @endif
                            @else
                                <span class="font-bold">Event Organizer</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
        
                @foreach($uninvitedFriends as $index => $friend)
                    <td class="border border-gray-300 px-4 py-2">
                        <div class="flex items-center">
                        @if($friend->portrait)
                            <img src="{{ $friend->portrait }}" class="block h-9 w-9 mr-2 rounded-full"/>
                        @else
                            <img src="/img/user.svg" class="block h-9 w-9 mr-2"/>
                        @endif
                        <span class="mr-2">{{ $friend->name }}</span>
                        </div>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ $friend->email }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <div class="flex justify-center" wire:loading.remove wire:click="sendInvite({{ $eventId }}, '{{ $friend->name }}', '{{ $friend->email }}')">
                            <x-danger-button class="px-4 py-2">
                                Send Invitation
                            </x-danger-button>
                        </div>
                    </td>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
