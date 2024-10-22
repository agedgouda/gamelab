<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif


    

    <form wire:submit="createGame">
        <div class="mt-4 mb-5">
            <x-input-label for="bgg-id" :value="__('Board Game Geek ID')" />

            <!-- Flex container for input and button -->
            <div class="flex items-center mt-1 mr-5">
                <!-- Adjust the width of the input field -->
                <x-text-input wire:model="bggId" id="bgg-id" class="w-48" type="text" name="bgg-id"/>
                
                <!-- Add margin to the left of the button for space -->
                <x-primary-button class="ml-4 pl-5">
                    {{ __('Add Game') }}
                </x-primary-button>
            </div>

            <x-input-error :messages="$errors->get('form.bggId')" class="mt-2" />
        </div>
    </form>
</div>
