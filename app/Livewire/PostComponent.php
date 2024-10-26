<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Game;
use App\Models\Event;

class PostComponent extends Component
{
    public $posts;
    public $content;
    public $type;
    public $postId; // For editing existing posts
    public $isEditMode = false;
    public $addPost = false;
    public $postableType;
    public $postableId;

    protected $rules = [
        'content' => 'required|string',
        'type' => 'required|in:Review,Video,After Action Report,Strategy Guide', // Add other types if necessary
    ];

    public function mount()
    {
        $this->posts = Post::all(); // Load all posts initially
    }

    public function createPost()
    {
        $this->validate();
        \Log::info(json_encode($this));
        $this->postableClass = $this->postableType === 'game' ? Game::class : Event::class;

        \Log::info(json_encode($this));

        Post::create([
            'user_id' => auth()->id(), // Assuming the user is authenticated
            'content' => $this->content,
            'type' => $this->type,
            'postable_type' => $this->postableClass,
            'postable_id' => $this->postableId,
        ]);

        // Reset form fields
        $this->reset(['content', 'type']);
        $this->posts = Post::all(); // Refresh the posts list
    }

    public function editPost($postId)
    {
        $post = Post::find($postId);
        $this->postId = $post->id;
        $this->content = $post->content;
        $this->type = $post->type;
    }

    public function updatePost()
    {
        $this->validate();
        
        $post = Post::find($this->postId);
        $post->update([
            'content' => $this->content,
            'type' => $this->type,
        ]);

        // Reset form fields
        $this->reset(['content', 'type']);
        $this->posts = Post::all(); // Refresh the posts list
    }

    public function deletePost($postId)
    {
        Post::destroy($postId);
        $this->posts = Post::all(); // Refresh the posts list
    }

    public function render()
    {
        return view('livewire.post-component');
    }
}
