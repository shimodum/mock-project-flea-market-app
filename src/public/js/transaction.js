document.addEventListener('DOMContentLoaded', function () {
    console.log("イベントデリゲーションのバインド");

    const chatContainer = document.querySelector('.chat-container');

    // =====================
    // 編集処理
    // =====================
    chatContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('edit-message')) {
            console.log("編集ボタンがクリックされました");
            const messageId = event.target.dataset.id;
            const editForm = document.querySelector(`#message-${messageId} .message-edit-form`);
            const messageContent = document.querySelector(`#message-${messageId} .message-content`);

            editForm.style.display = 'block';
            messageContent.style.display = 'none';
        }
    });

    // =====================
    // 保存処理（フォームの中の保存ボタン）
    // =====================
    chatContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('save-edit')) {
            console.log("保存ボタンがクリックされました");
            const messageId = event.target.dataset.id;
            const editForm = document.querySelector(`#message-${messageId} .message-edit-form`);
            const newMessage = editForm.querySelector('textarea').value;

            fetch(`/transactions/messages/${messageId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: newMessage })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("編集成功しました");
                    const messageContent = document.querySelector(`#message-${messageId} .message-content p`);
                    messageContent.textContent = newMessage;

                    // 表示切り替え
                    editForm.style.display = 'none';
                    messageContent.parentNode.style.display = 'block';
                } else {
                    alert('メッセージの更新に失敗しました');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });

    // =====================
    // キャンセル処理
    // =====================
    chatContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('cancel-edit')) {
            console.log("キャンセルボタンがクリックされました");
            const messageId = event.target.dataset.id;

            // 正しいターゲットの取得
            const editForm = document.querySelector(`#message-${messageId} .message-edit-form`);
            const messageContent = document.querySelector(`#message-${messageId} .message-content`);

            // 表示切り替え
            editForm.style.display = 'none';
            messageContent.style.display = 'block';
        }
    });

    // =====================
    // 削除処理
    // =====================
    chatContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('delete-message')) {
            console.log("削除ボタンがクリックされました");
            const messageId = event.target.dataset.id;

            if (confirm('本当に削除しますか？')) {
                fetch(`/transactions/messages/${messageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`message-${messageId}`).remove();
                        console.log("削除成功しました");
                    } else {
                        alert('メッセージの削除に失敗しました');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    });

    // =====================
    // モーダル処理
    // =====================
    const completeTransactionButton = document.getElementById('completeTransactionButton');
    const modal = document.getElementById('evaluationModal');
    const closeModal = document.getElementById('closeModal');
    const stars = document.querySelectorAll('.star');
    const ratingValue = document.getElementById('ratingValue');

    if (completeTransactionButton && modal && closeModal) {
        console.log("モーダル要素が見つかりました。イベントをバインドします。");

        completeTransactionButton.addEventListener('click', function () {
            console.log("モーダル表示処理が発火しました。");
            modal.style.display = 'block';
        });

        closeModal.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // ⭐️ 星評価のクリック処理修正
        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                console.log(`星 ${index + 1} がクリックされました`);
                ratingValue.value = index + 1;

                stars.forEach((s, i) => {
                    s.src = i < index + 1 ? "/images/star_filled.png" : "/images/star_empty.png";
                });
            });
        });
    } else {
        console.error("モーダル関連の要素が見つかりません。Bladeテンプレートが正しく読み込まれているか確認してください。");
    }
});
