document.addEventListener('DOMContentLoaded', function () {
    // モーダル関連の要素を取得
    const completeTransactionButton = document.getElementById('completeTransactionButton');
    const modal = document.getElementById('evaluationModal');
    const closeModal = document.getElementById('closeModal');
    const stars = document.querySelectorAll('.star');
    const ratingValue = document.getElementById('ratingValue');

    // メッセージ入力欄の取得
    const messageTextarea = document.getElementById('messageTextarea');

    // モーダル要素の確認
    if (completeTransactionButton && modal && closeModal) {
        console.log("モーダル要素が見つかりました。イベントをバインドします。");

        // モーダル表示処理
        completeTransactionButton.addEventListener('click', function () {
            console.log("モーダル表示処理が発火しました。");
            modal.style.display = 'block';
        });

        // モーダル閉じる処理
        closeModal.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        // モーダル外クリックで閉じる
        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
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
    } else {
        console.error("モーダル関連の要素が見つかりません。Bladeテンプレートが正しく読み込まれているか確認してください。");
    }

    // メッセージ入力のローカルストレージ保持
    if (messageTextarea) {
        // ローカルストレージから値を復元
        const savedMessage = localStorage.getItem('transaction_message');
        if (savedMessage) {
            messageTextarea.value = savedMessage;
        }

        // テキストエリアの内容が変更されたら保存
        messageTextarea.addEventListener('input', (event) => {
            localStorage.setItem('transaction_message', event.target.value);
        });

        // フォームが送信されたらローカルストレージのデータをクリア
        const form = messageTextarea.closest('form');
        form.addEventListener('submit', () => {
            localStorage.removeItem('transaction_message');
        });
    }

    // ハンバーガーメニューの開閉
    window.toggleSidebar = function () {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('open');
    };
});
