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
    public $title;
    public $postId; // For editing existing posts
    public $isEditMode = false;
    public $showDetails = false;
    public $selectedPostId;
    public $addPost = false;
    public $postableType;
    public $postableId;
    public $selectedType = 'After Action Report';
    public $filteredPosts = [];
    public $postableClass;

    public function filterByType($type)
    {
        $this->selectedType = $type;
        $this->showDetails = false; // Reset any post details view
        $this->filteredPosts = Post::where('type', $type)->get();
        $this->selectedPostId = null;
    }

    public function setSelectedPostId($selectedPostId){
        $this->selectedPostId = $selectedPostId;
    }

    public function getFilteredPostsProperty()
    {
        //$this->postableClass = $this->postableType === 'game' ? Game::class : Event::class;
        return $this->selectedType
                ? Post::where('type', $this->selectedType)
                    ->where('postable_class', $this->postableClass)
                    ->where('postable_id', $this->postableId)
                    ->get()
                : Post::where('postable_class', $this->postableClass)
                    ->where('postable_id', $this->postableId)
                    ->get();
    }

    protected $rules = [
        'content' => 'required|string',
        'type' => 'required|in:Review,Video,After Action Report,Strategy Guide', // Add other types if necessary
    ];

    public function mount()
    {
        $this->postableClass = $this->postableType === 'game' ? Game::class : Event::class;
        $this->filteredPosts = Post::where('postable_type', $this->postableClass)
                ->where('postable_id', $this->postableId)
                ->get();
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
            'title' => $this->title,
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
