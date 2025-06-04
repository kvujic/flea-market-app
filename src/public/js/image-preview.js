document.addEventListener('DOMContentLoaded', function () {
    console.log('✅ image-preview.js loaded');

    const inputs = document.querySelectorAll('.image-input');

    inputs.forEach(function (input) {
        const previewSelector = input.dataset.previewTarget;
        const labelSelector = input.dataset.labelTarget;

        const container = input.closest('.image-upload__frame');
        const preview = container ? container.querySelector(previewSelector) : null;
        const label = container ? container.querySelector(labelSelector) : null;

        console.log('🖼️ preview:', preview);
        console.log('🔤 label:', label);

        if (!preview) return;

        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            console.log('📁 選択されたファイル:', file);

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };

                reader.readAsDataURL(file);

            } else {
                preview.src = preview.dataset.defaultSrc || '';
                preview.style.display = preview.src ? 'block' : 'none';
            }
        });
    });
});