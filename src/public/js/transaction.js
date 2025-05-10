document.addEventListener('DOMContentLoaded', function () {
    // 「取引を完了する」ボタンをクリックした時の処理
    const completeButton = document.getElementById('completeTransactionButton');
    const modal = document.getElementById('evaluationModal');

    if (completeButton && modal) {
        completeButton.addEventListener('click', function () {
            modal.style.display = 'block';
        });
    }

    // モーダルの外側をクリックしたら閉じる
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
