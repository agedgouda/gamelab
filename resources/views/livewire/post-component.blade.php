<div>
    <h3 class="mt-4">All Posts</h3>
    <ul>
        @foreach($posts as $post)
            <li>
                <div>{{ $post->content }} ({{ $post->type }})</div>
                <button wire:click="editPost({{ $post->id }})">Edit</button>
                <button wire:click="deletePost({{ $post->id }})">Delete</button>
            </li>
        @endforeach
    </ul>
    @if($addPost)
        <form wire:submit.prevent="{{ $postId ? 'updatePost' : 'createPost' }}">
            <div>
                <textarea wire:model.defer="content" placeholder="Enter post content" required></textarea>
            </div>
            <div>
                <select wire:model.defer="type" required>
                    <option value="">Select Type</option>
                    <option value="Review">Review</option>
                    <option value="Video">Video</option>
                    <option value="After Action Report">After Action Reports</option>
                    <option value="Strategy Guide">Strategy Guides</option>
                </select>
            </div>
            <button type="submit">{{ $postId ? 'Update' : 'Create' }} Post</button>
        </form>
    @else
        <x-primary-button class="ml-4 pl-5" wire:click="$set('addPost', true)">
            {{ __('Create Post') }}
        </x-primary-button>
    @endif
</div>
