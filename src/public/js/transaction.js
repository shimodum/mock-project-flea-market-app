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

            const messageId = event.target.dataset.id || event.target.closest('.message-edit-form').querySelector('.save-edit').dataset.id;

            console.log(`messageId = ${messageId}`);

            const editForm = document.querySelector(`#message-${messageId} .message-edit-form`);
            const messageContent = document.querySelector(`#message-${messageId} .message-content`);

            console.log("editForm:", editForm);
            console.log("messageContent:", messageContent);

            if (editForm && messageContent) {
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
    const ratingForm = document.getElementById('ratingForm');

    if (completeTransactionButton && modal) {
        console.log("モーダル要素が見つかりました。イベントをバインドします。");

        completeTransactionButton.addEventListener('click', function () {
            console.log("モーダル表示処理が発火しました。");
            modal.style.display = 'block';
        });

        // ★ 修正: モーダルの閉じるボタンのバインド
        if (closeModal) {
            closeModal.addEventListener('click', function () {
                modal.style.display = 'none';
            });
        }

        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // ★ 修正: 星のクリック処理
        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                ratingValue.value = index + 1;

                stars.forEach((s, i) => {
                    if (i < index + 1) {
                        s.src = `/images/modal_star_filled.png`;  // ★ パス修正
                    } else {
                        s.src = `/images/modal_star_empty.png`;  // ★ パス修正
                    }
                });
            });
        });

        // =====================
        // モーダルの送信処理
        // =====================
        ratingForm.addEventListener('submit', function (e) {
            e.preventDefault();

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ rating: ratingValue.value })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.replace(data.redirect);
                } else {
                    alert('評価の送信に失敗しました');
                }
            })
            .catch(error => {
                console.error('Fetchエラー:', error);
                alert('評価の送信に失敗しました。ネットワークエラーかもしれません。');
            });
        });
    } else {
        console.error("モーダル関連の要素が見つかりません。Bladeテンプレートが正しく読み込まれているか確認してください。");
    }

    // ==================
    // 入力内容の保存処理
    // ==================
    const messageTextarea = document.getElementById('messageTextarea');

    messageTextarea.addEventListener('input', function () {
        localStorage.setItem('chat_message_' + window.location.pathname, messageTextarea.value);
        console.log("メッセージが保存されました:", messageTextarea.value);
    });

    // ========================
    // ページ読み込み時の復元処理
    // ========================
    const savedMessage = localStorage.getItem('chat_message_' + window.location.pathname);
    if (savedMessage) {
        messageTextarea.value = savedMessage;
        console.log("メッセージが復元されました:", savedMessage);
    }

    // ====================================
    // メッセージ送信時に LocalStorage を削除
    // ====================================
    const messageForm = document.querySelector('form[action*="chat_messages.store"]');
    if (messageForm) {
        messageForm.addEventListener('submit', function () {
            localStorage.removeItem('chat_message_' + window.location.pathname);
            console.log("メッセージが送信されたので削除しました");
        });
    }
});
