document.addEventListener('alpine:init', () => {
    Alpine.data('fileUploadComponent', () => ({
        previewUrl: null, // Preview URL will be set via Livewire event
        fileError: null,
        showModal: false,
        cropperInstance: null,
        croppedBlob: null,

        init() {
            // Listen for Livewire event to update previewUrl
            Livewire.on('setPreviewUrl', (url) => {
                this.previewUrl = url;
            });

            // Listen for file error messages from Livewire
            Livewire.on('file-uploaded', () => {
                this.previewUrl = null; // Clear the preview
                this.$refs.fileInput.value = ''; // Reset file input
            });

            // Update fileError when Livewire validation errors change
            Livewire.hook('message.processed', (message, component) => {
                this.fileError = component.$wire.entangle('fileError'); // Dynamically bind error messages
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
