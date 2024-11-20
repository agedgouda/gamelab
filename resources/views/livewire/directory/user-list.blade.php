<div class="p-7">

    <table>
        @foreach($users as $user)
            <tr 
                class="hover:bg-teal-700 text-yellow-900 hover:text-yellow-400 cursor-pointer" 
                onclick="window.location='{{ route('directory-entry', ['userId' => $user->id]) }}'"
            >
            <td class="pr-3 border border-gray-300 p-2">
                
                <div class="flex">
                    @if($user->portrait)
                        <img src="{{ $user->portrait }}" class="block h-9 w-9 rounded-full"/>
                    @else
                        <img src="img/user.svg" class="block h-9 w-9"/>
                    @endif
                    <div class="mt-1 ml-3">{{ $user->name }}</div>
                    
                    @if($friendOf->contains($user->id) && !$friends->contains($user->id))
                    <img src="img/friendof.svg" class="block h-9 w-9 rounded-full"/>
                    @endif
                    
                    @if($friends->contains($user->id) && !$friendOf->contains($user->id) )
                    <img src="img/friend.svg" class="block h-9 w-9 rounded-full"/>
                    @endif
                    
                    @if($friends->contains($user->id) && $friendOf->contains($user->id) )
                    <img src="img/both.svg" class="block h-9 w-9 rounded-full"  />
                    @endif
                </div>
            </td>
            <td class="pr-3 border border-gray-300 p-2">{{ $user->email }}</td>
            </tr>
        @endforeach
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

</div>
