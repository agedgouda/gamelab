<div x-data="fileUploadComponent">
    <!-- File Input -->
    <form wire:submit.prevent="uploadFile">
        <input 
            type="file" 
            x-ref="fileInput" 
            @change="previewFile" 
            wire:model="file"
        >
        
        <template x-if="fileError">
            <span class="error" x-text="fileError"></span>
        </template>
        
        <!-- Preview Area -->
        <template x-if="previewUrl">
            <div class="preview">
                <h4>Original Image Preview (Will Be Uploaded)</h4>
                <img :src="previewUrl" alt="File Preview" class="img-preview">
            </div>
        </template>

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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('fileUploadComponent', () => ({
            previewUrl: @entangle('previewUrl'),
            fileError: null,

            init() {
                this.$watch('$wire.file', () => {
                    this.fileError = @js($errors->first('file') ?? null);
                });

                Livewire.on('fileUploaded', () => {
                    this.previewUrl = null; // Clear the preview
                    this.$refs.fileInput.value = ''; // Reset file input
                });

                Livewire.hook('message.processed', (message, component) => {
                    this.fileError = @js($errors->first('file') ?? null);
                });
            },

            previewFile() {
                const fileInput = this.$refs.fileInput.files[0];
                if (fileInput) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previewUrl = e.target.result;
                    };
                    reader.readAsDataURL(fileInput);
                }
            },
        }));
    });
</script>
