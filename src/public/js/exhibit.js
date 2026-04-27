const imageInput = document.getElementById('image');
const previewImage = document.getElementById('image-preview');
const imageArea = document.getElementById('image-area');

if (imageInput && previewImage && imageArea) {
    const resetPreview = () => {
        previewImage.src = '';
        previewImage.classList.add('is-hidden');
        imageArea.classList.remove('exhibit__image-area--preview');
    };

    imageInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) {
            resetPreview();
            return;
        }
        if (!file.type.startsWith('image/')) {
            resetPreview();
            return;
        }
        const reader = new FileReader();
        reader.onload = function (loadEvent) {
            previewImage.src = loadEvent.target.result;
            previewImage.classList.remove('is-hidden');
            imageArea.classList.add('exhibit__image-area--preview');
        };
        reader.readAsDataURL(file);
    });
}

document.querySelectorAll('.exhibit__category-input').forEach(function (input) {
    if (input.checked) {
        input.nextElementSibling.classList.add('exhibit__category-tag--active');
    }
    input.addEventListener('change', function () {
        this.nextElementSibling.classList.toggle('exhibit__category-tag--active', this.checked);
    });
});
