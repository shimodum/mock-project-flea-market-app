document.addEventListener('DOMContentLoaded', function () {
    console.log("イベントデリゲーションのバインド");

    const chatContainer = document.querySelector('.chat-container');

    // =====================
    // 編集ボタン処理
    // =====================
    chatContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('edit-message')) {
            console.log("編集ボタンがクリックされました");
            const messageId = event.target.dataset.id;
            const editForm = document.querySelector(`#message-${messageId} .message-edit-form`);
            const messageContent = document.querySelector(`#message-${messageId} .message-content`);

            // フォームを表示し、元のメッセージを非表示にする
            editForm.style.display = 'block';
            messageContent.style.display = 'none';
        }
    });

    // =====================
    // キャンセルボタン処理
    // =====================
    chatContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('cancel-edit')) {
            console.log("キャンセルボタンがクリックされました");

            // 親要素から data-id を探す
            const messageId = event.target.dataset.id || event.target.closest('.message-edit-form').querySelector('.save-edit').dataset.id;

            console.log(`messageId = ${messageId}`);

            const editForm = document.querySelector(`#message-${messageId} .message-edit-form`);
            const messageContent = document.querySelector(`#message-${messageId} .message-content`);

            console.log("editForm:", editForm);
            console.log("messageContent:", messageContent);

            if (editForm && messageContent) {
                // フォームを非表示にし、元のメッセージを再表示
                editForm.style.display = 'none';
                messageContent.style.display = 'block';
                console.log("キャンセル処理が正常に実行されました");
            } else {
                console.error(`キャンセル処理でフォームが見つかりませんでした: messageId = ${messageId}`);
            }
        }
    });



    // =====================
    // 保存ボタン処理
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
                    const messageContent = document.querySelector(`#message-${messageId} .message-content p`);
                    messageContent.textContent = newMessage;
                    editForm.style.display = 'none';
                    messageContent.parentNode.style.display = 'block';
                    console.log("編集成功しました");
                } else {
                    alert('メッセージの更新に失敗しました');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });

    // =====================
    // 削除ボタン処理
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
});
