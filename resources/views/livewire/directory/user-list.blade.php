<div>
    
    <table>
    @foreach($users as $user)
        <tr class="hover:bg-gray-100 cursor-pointer  {{ $loop->odd ? 'bg-gray-200' : '' }}">
            <td class=" p-3 w-14 h-14">
                @if($user->portrait)
                <img src="{{$user->portrait}}" class="block w-14 h-14 rounded-full"/>
            @else
                <x-application-logo class="block h-9 w-14 fill-current text-gray-800" />
            @endif
            <td>
            <td class="w-80">{{ $user->name }}</td>
            <td class="pr-3">{{ $user->email }}</td>
        </tr>
    @endforeach
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

</div>
