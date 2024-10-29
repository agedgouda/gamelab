<div>

    <div class="grid grid-cols-[15%,85%] ">
        <div class="bg-gray-200 pl-3 pt-3  flex flex-col justify-between h-48">
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

    <div class="ml-5">
        
        @if($addPost)
            <form wire:submit.prevent="{{ $postId ? 'updatePost' : 'createPost' }}">  
                <div class="mb-5">
                    <div class="flex items-center">
                        <x-text-input wire:model="title" id="title" class="w-full" type="text" name="title" placeholder="Title"  />
                    </div>
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <div>
                    <x-textarea wire:model.defer="content" rows="14" id="content-editor" class="w-full" name="content" placeholder="Enter post content"   />
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-5">
                    <x-secondary-button wire:click="$set('addPost', false)">
                    {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button id="submit" class="ml-4"  type="submit">
                        {{ $postId ? 'Update' : 'Create' }} Post
                    </x-primary-button>
                </div>
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
                            class="cursor-pointer {{ $loop->odd ? 'bg-gray-200' : '' }} hover:bg-gray-300 hover:text-white font-medium pt-2 pb-1 pl-1 ml-2 {{ $selectedType === $post->type ? '' : 'hidden' }}"
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
                            @endif
                            <button wire:click="setSelectedPostId(false)">Back</button>
                        </div>
                        @endif
                    </li>
                @endforeach
            </ul>
            @endif

            @script
            <script>

                const targetNode = document.body; // or a specific parent element
                let initialized = false;



                function initializeEditor() {
                    const contentEditor = document.querySelector('#content-editor');


                    if (contentEditor && !initialized) {
                        initialized = true;
                        ClassicEditor.create(contentEditor)
                            .then(editor => {
                                window.editor = editor;
                                // Sync editor data to Livewire on form submit
                                const submitButton = document.querySelector("#submit");
                                if (submitButton) {
                                    submitButton.addEventListener("click", (e) => {
                                        e.preventDefault();  // Prevents the form from submitting immediately
                                        $wire.set('content', editor.getData()).then(() => {
                                            // Submit the form after the Livewire property is set
                                            $wire.call('{{ $postId ? 'updatePost' : 'createPost' }}');
                                        });
                                    });
                                }
                            })
                            .catch(error => {
                                console.error(error);
                            });
                    }
                }

                // Create a new MutationObserver instance
                const observer = new MutationObserver((mutationsList) => {
                    for (const mutation of mutationsList) {
                        if (mutation.type === 'childList') {
                            const contentEditor = document.querySelector('#content-editor');
                            if (contentEditor) {
                                // Initialize the editor only if it's not already initialized
                                initializeEditor();
                            } else if (initialized) {
                                initialized = false; // Reset when editor is removed
                                // Optionally, if you want to destroy the editor instance when removed
                                if (window.editor) {
                                    window.editor.destroy();
                                    window.editor = null;
                                }
                            }
                        }
                    }
                });

                // Start observing the target node for mutations
                observer.observe(targetNode, { childList: true, subtree: true });

                // Listen for Livewire's validation failure event
                Livewire.on('validationFailed', () => {
                    if (window.editor) {
                        window.editor.destroy();
                        window.editor = null; // Clear the editor reference
                        initialized = false;
                    }

                    initializeEditor();
                });


                
            </script>
            @endscript
    </div>
</div>
