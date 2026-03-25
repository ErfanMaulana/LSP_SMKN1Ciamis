<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
    (function () {
        const penjelasanInput = document.getElementById('penjelasan');
        const imageInput = document.getElementById('image');
        const previewImage = document.getElementById('uploadPreviewImage');
        const previewPlaceholder = document.getElementById('uploadPreviewPlaceholder');
        const removeImageCheckbox = document.getElementById('remove_image');

        if (penjelasanInput && window.ClassicEditor) {
            ClassicEditor
                .create(penjelasanInput, {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', '|',
                        'link', 'bulletedList', 'numberedList', '|',
                        'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ]
                })
                .catch(function (error) {
                    console.error(error);
                });
        }

        if (!imageInput || !previewImage || !previewPlaceholder) {
            return;
        }

        function showPlaceholder() {
            previewImage.classList.remove('show');
            previewImage.removeAttribute('src');
            previewPlaceholder.classList.remove('hide');
        }

        function showImage(src) {
            previewImage.src = src;
            previewImage.classList.add('show');
            previewPlaceholder.classList.add('hide');
        }

        imageInput.addEventListener('change', function (event) {
            const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;

            if (!file) {
                if (removeImageCheckbox && removeImageCheckbox.checked) {
                    showPlaceholder();
                    return;
                }
                if (previewImage.getAttribute('src')) {
                    previewImage.classList.add('show');
                    previewPlaceholder.classList.add('hide');
                } else {
                    showPlaceholder();
                }
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                showImage(e.target.result);
                if (removeImageCheckbox) {
                    removeImageCheckbox.checked = false;
                }
            };
            reader.readAsDataURL(file);
        });

        if (removeImageCheckbox) {
            removeImageCheckbox.addEventListener('change', function () {
                if (this.checked && !(imageInput.files && imageInput.files.length > 0)) {
                    showPlaceholder();
                }
            });
        }

        if (!previewImage.getAttribute('src')) {
            showPlaceholder();
        }
    })();
</script>
