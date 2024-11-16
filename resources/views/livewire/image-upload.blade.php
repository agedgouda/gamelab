
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

