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

    // 数値のみ入力可能にする処理
    const priceInput = document.getElementById('price');
    priceInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, ''); // 数字以外を削除
    });
});
