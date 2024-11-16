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
                <!-- Limit preview to 300x300 for display -->
                <img :src="previewUrl" alt="File Preview" class="img-preview" style="max-width: 300px; max-height: 300px;">
                
                <!-- Crop Button -->
                <button type="button" @click="openModal" 
                    class="btn-crop 
                        inline-flex 
                        items-center 
                        px-4 
                        py-2 
                        bg-red-600 
                        border 
                        border-transparent 
                        rounded-md 
                        font-semibold 
                        text-xs 
                        text-white 
                        uppercase 
                        tracking-widest 
                        hover:bg-red-500 
                        active:bg-red-700 
                        focus:outline-none 
                        focus:ring-2 
                        focus:ring-red-500 
                        focus:ring-offset-2">
                        Crop Image
                    </button>

                <!-- Upload Button -->
                <x-primary-button class="btn-upload mt-3 ml-5">
                    {{ __('Upload') }}
                </x-primary-button>
            </div>
        </template>
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
   
    <!-- Cropper Modal -->
    <div x-show="showModal" class="modal-cropper" style="display: none;">
        <div class="modal-content-cropper">
            <h4>Crop Your Image</h4>
            <img :src="previewUrl" alt="Image to Crop" id="modalImage" class="img-preview">

            <!-- Zoom Controls -->
            <div class="zoom-controls mt-3">                
                <x-zoom-button @click="zoomOut" class="btn-zoom-out mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 ">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM13.5 10.5h-6" />
                    </svg>
                </x-zoom-button>
                
                <x-zoom-button @click="zoomIn" class="btn-zoom-in mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM10.5 7.5v6m3-3h-6" />
                      </svg>
                </x-zoom-button>
            </div>
            
            <x-primary-button type="button" @click="cropImage" class="btn-crop mt-3">
                {{ __('Apply Crop') }}
            </x-primary-button>
            <x-secondary-button type="button" @click="closeModal" class="btn-close mt-3">
                {{ __('Cancel') }}
            </x-secondary-button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('fileUploadComponent', () => ({
            previewUrl: @entangle('previewUrl'),
            fileError: null,
            showModal: false,
            cropperInstance: null,
            croppedBlob: null,

            init() {
                this.$watch('$wire.file', () => {
                    this.fileError = @js($errors->first('file') ?? null);
                });

                Livewire.on('file-uploaded', () => {
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
                        const originalImage = new Image();
                        originalImage.onload = () => {
                            // Resize image for preview only (max 300x300)
                            const resizedCanvas = this.resizeCanvas(originalImage, 300, 300);
                            this.previewUrl = resizedCanvas.toDataURL(); // Preview image data URL
                        };
                        originalImage.src = e.target.result;
                    };
                    reader.readAsDataURL(fileInput);
                }
            },

            openModal() {
                this.showModal = true;
                this.$nextTick(() => {
                    const image = document.getElementById('modalImage');
                    if (this.cropperInstance) {
                        this.cropperInstance.destroy();
                    }
                    // Initialize Cropper for the original image
                    this.cropperInstance = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 0.8, // Allow larger crop area
                        responsive: true, // Allow responsiveness
                        background: true, // Show the background image while cropping
                    });
                });
            },

            closeModal() {
                this.showModal = false;
                if (this.cropperInstance) {
                    this.cropperInstance.destroy();
                    this.cropperInstance = null;
                }
            },

            cropImage() {
                if (this.cropperInstance) {
                    const croppedCanvas = this.cropperInstance.getCroppedCanvas();
                    // Optionally resize cropped image if needed
                    const resizedCanvas = this.resizeCanvas(croppedCanvas, 300, 300);
                    this.previewUrl = resizedCanvas.toDataURL(); // Update preview URL

                    resizedCanvas.toBlob((blob) => {
                        this.croppedBlob = blob;
                        this.uploadCroppedImage();
                    });

                    this.closeModal();
                }
            },

            resizeCanvas(image, maxWidth, maxHeight) {
                const width = image.width;
                const height = image.height;
                const aspectRatio = width / height;

                let newWidth = width;
                let newHeight = height;

                if (width > maxWidth || height > maxHeight) {
                    if (aspectRatio > 1) {
                        newWidth = maxWidth;
                        newHeight = maxWidth / aspectRatio;
                    } else {
                        newHeight = maxHeight;
                        newWidth = maxHeight * aspectRatio;
                    }
                }

                const resizedCanvas = document.createElement('canvas');
                resizedCanvas.width = newWidth;
                resizedCanvas.height = newHeight;

                const ctx = resizedCanvas.getContext('2d');
                ctx.drawImage(image, 0, 0, newWidth, newHeight);

                return resizedCanvas;
            },

            uploadCroppedImage() {
                if (this.croppedBlob) {
                    const file = new File([this.croppedBlob], 'resized-cropped-image.jpg', { type: 'image/jpeg' });
                    this.$wire.upload('file', file);
                }
            },

            // Zoom in method
            zoomIn() {
                if (this.cropperInstance) {
                    this.cropperInstance.zoom(0.1); // Zoom in by 10%
                }
            },

            // Zoom out method
            zoomOut() {
                if (this.cropperInstance) {
                    this.cropperInstance.zoom(-0.1); // Zoom out by 10%
                }
            },
        }));
    });
</script>
