document.addEventListener('DOMContentLoaded', function () {

    const inputs = document.querySelectorAll('.image-input');

    inputs.forEach(function (input) {
        const previewSelector = input.dataset.previewTarget;
        const labelSelector = input.dataset.labelTarget;

        const container = input.closest('.image-upload__frame');
        const preview = container ? container.querySelector(previewSelector) : null;
        const label = container ? container.querySelector(labelSelector) : null;

        if (!preview) return;

        input.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');

                    const uploadLabel = input.closest('label.custom-file__upload');
                    const uploadContainer = document.getElementById('upload-button-container');

                    if (uploadLabel && uploadContainer) {
                        uploadLabel.style.position = 'static';
                        uploadLabel.style.transform = 'none';
                        uploadLabel.style.top = 'unset';
                        uploadLabel.style.left = 'unset';

                        uploadContainer.appendChild(uploadLabel);
                    }
                };

                reader.readAsDataURL(file);

            } else {
                preview.src = preview.dataset.defaultSrc || '';
                preview.style.display = preview.src ? 'block' : 'none';
            }
        });
    });
});

