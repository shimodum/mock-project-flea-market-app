document.getElementById('profile_image').addEventListener('change', function(event) {
    const [file] = event.target.files;
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
