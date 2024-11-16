<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;
use App\Models\User;


class UserInformation extends Component
{
 
    public $file;
    public $changeImage = false;

    public function toggleUpload() {
        $this->changeImage = !$this->changeImage;
    }

    public function render()
    {
         return view('livewire.user-information');
    }

    public function fetchImages()
    {
        // Get a list of all files in the 'users' folder on S3
        $files = Storage::disk('s3')->files('users');  // Fetch all files from 'users' folder

        // Filter out only the image files (optional, if you only want images)
        $this->images = array_filter($files, function ($file) {
            return in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']);
        });

        // Convert file paths to full URLs
        $this->images = array_map(function ($file) {
            return Storage::disk('s3')->url($file);
        }, $this->images);
    }

    #[On('file-uploaded')]
    public function newUserImage($fileUrl)
    {
        auth()->user()->portrait = $fileUrl;
        auth()->user()->save();
        $this->changeImage = false;
    }
}
