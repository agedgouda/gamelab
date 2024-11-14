<div>
    <!-- File Input -->
    <form wire:submit.prevent="uploadFile">
        <input type="file" wire:model="file" id="fileInput" onchange="previewFile()">
        
        @error('file') 
            <span class="error">{{ $message }}</span>
        @enderror
        
        <!-- Preview Area -->
        @if ($previewUrl)
            <div class="preview">
                <img src="{{ $previewUrl }}" alt="File Preview" class="img-preview">
            </div>
        @endif

        <button type="submit" class="btn-upload">Upload</button>
    </form>

    @if (session()->has('message'))
        <div>{{ session('message') }}</div>
    @endif

    <!-- Display the images after upload -->
    <div>
        @foreach($images as $image)
            <div>
                <img src="{{ $image }}" alt="Image" style="max-width: 100px; max-height: 100px;">
            </div>
        @endforeach
    </div>
</div>

<!-- JavaScript for File Preview -->
<script>
    function previewFile() {
        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];
        
        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                @this.previewUrl = e.target.result;  // Set the preview URL in Livewire
            };
            
            reader.readAsDataURL(file); // Convert the file to a Data URL
        }
    }
</script>
