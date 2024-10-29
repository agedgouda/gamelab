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
        $this->addPost = false;
        $this->filteredPosts = Post::where('type', $type)
        ->where('postable_type', $this->postableClass)
        ->where('postable_id', $this->postableId)
        ->get();
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
                    ->where('postable_type', $this->postableClass)
                    ->where('postable_id', $this->postableId)
                    ->get()
                : Post::where('postable_type', $this->postableClass)
                    ->where('postable_id', $this->postableId)
                    ->get();
    }

    protected $rules = [
        'title' => 'required|string',
        'content' => 'required|string',
    ];

    public function mount()
    {
        $this->postableClass = $this->postableType === 'game' ? Game::class : Event::class;
        $this->filterByType($this->selectedType);
        $this->reset(['title', 'content']);
        session()->forget('errors');
    }

    public function createPost()
    {
        
        \Log::info(['title' => $this->title, 'content' => $this->content]);
        try {
            $this->validate();
            
            // Continue with form submission code here
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validationFailed'); // Emit a custom event
            throw $e; // Rethrow the exception so errors are handled normally
        }

        $this->postableClass = $this->postableType === 'game' ? Game::class : Event::class;

        Post::create([
            'user_id' => auth()->id(), // Assuming the user is authenticated
            'content' => $this->content,
            'type' => $this->selectedType,
            'postable_type' => $this->postableClass,
            'postable_id' => $this->postableId,
            'title' => $this->title,
        ]);

        $this->filterByType($this->selectedType);

        // Reset form fields
        $this->reset(['content','title']);
        $this->addPost = false;
    }

    public function editPost($postId)
    {

        $post = Post::find($postId);
        $this->postId = $post->id;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->addPost = true;
    }

    public function updatePost()
    {
        $this->validate();
        
        $post = Post::find($this->postId);
        $post->update([
            'content' => $this->content,
            'type' => $this->selectedType,
        ]);

        // Reset form fields
        $this->filterByType($this->selectedType);

        // Reset form fields
        $this->reset(['content', 'type','title']);

        $this->addPost = false;
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
