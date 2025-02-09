document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    imageInput.addEventListener('change', function (event) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.querySelector('.uploaded-image');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    });
});
