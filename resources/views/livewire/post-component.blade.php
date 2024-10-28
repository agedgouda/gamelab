<div>

    <div class="grid grid-cols-[15%,85%] h-96">
        <div class="bg-gray-200 pl-3 pt-3  flex flex-col justify-between h-full">
            <!-- Filter Items Container -->
            <div>
                <div wire:click="filterByType('After Action Report')" 
                    class="cursor-pointer hover:font-bold {{ $selectedType === 'After Action Report' ? 'font-bold' : '' }}">
                    After Action Reports
                </div>
                <div wire:click="filterByType('Review')" 
                    class="cursor-pointer hover:font-bold {{ $selectedType === 'Review' ? 'font-bold' : '' }}">
                    Reviews
                </div>
                <div wire:click="filterByType('Strategy Guide')" 
                    class="cursor-pointer hover:font-bold {{ $selectedType === 'Strategy Guide' ? 'font-bold' : '' }}">
                    Strategy Guides
                </div>
                <div wire:click="filterByType('Video')" 
                    class="cursor-pointer hover:font-bold {{ $selectedType === 'Video' ? 'font-bold' : '' }}">
                    Videos
                </div>
            </div>

    @if(!$addPost)
    <div class="mt-4 mb-5"> 
        <x-primary-button class="ml-4 pl-5" wire:click="$set('addPost', true)">
            {{ __('Add Post') }}
        </x-primary-button>
    </div>
    @endif
</div>

    <div>
        
    @if($addPost)
        <form wire:submit.prevent="{{ $postId ? 'updatePost' : 'createPost' }}">  
            <div class="mt-4 mb-5">
                <x-input-label for="title" :value="__('Name')" />
                <div class="flex items-center">
                    <x-text-input wire:model="title" id="title" class="w-full" type="text" name="title" placeholder="Title" required />
                </div>
                <x-input-error :messages="$errors->get('form.title')" class="mt-2" />
            </div>
            <div>
                
                <x-textarea wire:model.defer="content" id="content-editor" class="w-full" name="content" placeholder="Enter post content"   />
            </div>
            <div>
                <x-select wire:model.defer="type" :options="[
                    '' => 'Select Type',
                    'Review' => 'Review',
                    'Video' => 'Video',
                    'After Action Report' => 'After Action Report',
                    'Strategy Guide' => 'Strategy Guide'
                ]" required />
            </div>
            <button id="submit" type="submit">{{ $postId ? 'Update' : 'Create' }} Post</button>
            <x-primary-button class="ml-4 pl-5" wire:click="$set('addPost', false)">
            {{ __('Cancel') }}
        </x-primary-button>
        </form>        
    @else
        <ul>
            @foreach($this->filteredPosts as $post)
   
                <li>         
                    <!-- Title: Show if the selected type matches and no post is selected -->
                     @if(!$selectedPostId)
                    <div 
                        wire:click="setSelectedPostId({{ $post->id }})" 
                        wire:loading.class="cursor-not-allowed"
                        wire:target="setSelectedPostId"
                        class="cursor-pointer {{ $loop->odd ? 'bg-gray-200 hover:text-white' : '' }} font-medium pt-2 pb-1 pl-1 ml-2 {{ $selectedType === $post->type ? '' : 'hidden' }}"
                    >
                        {{ $post->title }} 
                        <div class="text-xs italic text-gray-500">{{ $post->user->name }}</div>
                    </div>
                    @elseif($selectedPostId === $post->id)
                    <!-- Details: Show only if this post is selected -->
                    <div class="mt-2 ml-2">
                        <div class="font-medium">{{ $post->title }}</div>
                        <div class="text-xs italic text-gray-500">{{ $post->user->name }}</div>
                        <div>{!! $post->content !!}</div>
                        @if(auth()->id() == $post->user_id)
                        <button wire:click="editPost({{ $post->id }})">Edit</button>
                        <button wire:click="deletePost({{ $post->id }})">Delete</button>
                        @endif
                        <button wire:click="setSelectedPostId(false)">Back</button>
                    </div>
                    @endif
                </li>
            @endforeach
        </ul>
        

        @endif
    </div>
    @script
    <script>

    const targetNode = document.body; // or a specific parent element
    let initialized = false;

    // Create a new MutationObserver instance
    const observer = new MutationObserver((mutationsList) => {
        for (const mutation of mutationsList) {
            if (mutation.type === 'childList') {
                // Check if #content-editor is in the DOM
                const contentEditor = document.querySelector('#content-editor');
                if (contentEditor && !initialized) {                       
                    initialized = true;
                    ClassicEditor.create(contentEditor)
                    .then(editor => {
                        window.editor = editor;

                        document.querySelector("#submit").addEventListener("click", () => {
                            $wire.set('content',  editor.getData());
                            console.log("feerer")
                        });


                        
                    })
                    .catch(error => {
                        console.error(error);
                    });
                }

                // Reset initialized if #content-editor is removed
                else if (!contentEditor && initialized) {
                    initialized = false;
                }
            }
        }
    });

    // Start observing the target node for mutations
    observer.observe(targetNode, { childList: true, subtree: true });
</script>
@endscript







</div>





    







</div>
