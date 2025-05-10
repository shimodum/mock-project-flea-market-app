document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star');
    const ratingValue = document.getElementById('ratingValue');
    const modal = document.getElementById('evaluationModal');
    const closeModal = document.getElementById('closeModal');
    const completeTransactionButton = document.getElementById('completeTransactionButton');

    // モーダル表示
    completeTransactionButton.addEventListener('click', function () {
        modal.style.display = 'block';
    });

    // モーダル閉じる処理
    closeModal.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    // 星評価のクリック処理
    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            ratingValue.value = index + 1;

            stars.forEach((s, i) => {
                s.src = i < index + 1 ? "/images/star_filled.png" : "/images/star_empty.png";
            });
        });
    });

    // モーダル外クリックで閉じる
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
