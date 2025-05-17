document.addEventListener('DOMContentLoaded', function() {
    // Image preview for cover image upload
    const fileInput = document.querySelector('input[type="file"][data-preview-target]');
    if (fileInput) {
        const previewContainer = document.querySelector('.image-preview-container');
        const previewImage = document.querySelector('.image-preview');
        const previewPlaceholder = document.querySelector('.image-preview-placeholder');
        const deleteButton = document.querySelector('.delete-image-btn');
        const deleteInput = document.querySelector('.delete-image-input');
        const uploadStatus = document.querySelector('.upload-status');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    if (previewPlaceholder) {
                        previewPlaceholder.classList.add('hidden');
                    }
                    if (uploadStatus) {
                        uploadStatus.textContent = `Image "${file.name}" sélectionnée`;
                        uploadStatus.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(file);
                previewContainer.classList.remove('border-dashed');
                previewContainer.classList.add('border-solid', 'border-indigo-600', 'border-2');
            }
        });

        // Support pour le drag & drop
        const dropZone = document.querySelector('.image-preview-container');
        if (dropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.classList.add('border-indigo-600', 'bg-indigo-50');
            }            function unhighlight(e) {
                dropZone.classList.remove('border-indigo-600', 'bg-indigo-50');
            }

            // Gestion de la suppression de l'image
            if (deleteButton && deleteInput) {
                deleteButton.addEventListener('click', function() {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
                        deleteInput.value = '1';
                        previewImage.classList.add('hidden');
                        previewPlaceholder.classList.remove('hidden');
                        deleteButton.parentElement.classList.add('hidden');
                        fileInput.value = '';
                    }
                });
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const file = dt.files[0];
                
                if (file && file.type.startsWith('image/')) {
                    fileInput.files = dt.files;
                    const event = new Event('change');
                    fileInput.dispatchEvent(event);
                }
            }
        }
    }
});
