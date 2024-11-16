<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ImageUpload extends Component
{
    use WithFileUploads;

    public $file;
    public $images = [];
    public $previewUrl;
    public $subdirectory;

    protected $rules = [
        'file' => 'required|file|mimes:jpeg,png,pdf|max:10240', // Validate file type and size
    ];

    public function uploadFile()
    {
        $this->validate();

        // Store the file on S3
        $path = $this->file->store($this->subdirectory, 's3'); // Store the file in the 'users' folder

        // Optionally, generate a URL to display for this file (this can be public or signed URL)
        $fileUrl = Storage::disk('s3')->url($path); // URL for the uploaded file

        // Flash message after successful upload
        session()->flash('message', 'File uploaded successfully!');

        $this->dispatch('file-uploaded', fileUrl: $fileUrl );

        // Fetch all image files from the 'users' directory in the S3 bucket
        // Reset the file input field after upload
        $this->reset('file');
    }

    public function render()
    {
        return view('livewire.image-upload');
    }
}
