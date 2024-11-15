<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UserInformation extends Component
{
    use WithFileUploads;

    public $file;
    public $images = [];
    public $previewUrl;

    protected $rules = [
        'file' => 'required|file|mimes:jpeg,png,pdf|max:10240', // Validate file type and size
    ];

    public function uploadFile()
    {
        $this->validate();

        // Store the file on S3
        $path = $this->file->store('users', 's3'); // Store the file in the 'users' folder

        // Optionally, generate a URL to display for this file (this can be public or signed URL)
        $fileUrl = Storage::disk('s3')->url($path); // URL for the uploaded file

        // Flash message after successful upload
        session()->flash('message', 'File uploaded successfully!');

        // Fetch all image files from the 'users' directory in the S3 bucket
        $this->fetchImages();

        // Reset the file input field after upload
        $this->reset('file');
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

    public function render()
    {
        $this->fetchImages();
        return view('livewire.user-information');
    }
}
