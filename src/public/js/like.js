// ページが完全に読み込まれたら処理を実行
document.addEventListener("DOMContentLoaded", function () {
    
    // すべての「いいね」ボタンを取得
    const likeButtons = document.querySelectorAll(".like-button");
    
    // CSRFトークンを取得 (LaravelではCSRF対策のため、リクエストにこのトークンが必要)
    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');

    // CSRFトークンが見つからない場合は処理を中断
    if (!csrfTokenElement) return;

    // メタタグからCSRFトークンの値を取得
    const csrfToken = csrfTokenElement.content;

    // すべての「いいね」ボタンに対してクリックイベントを設定
    likeButtons.forEach(button => {
        button.addEventListener("click", function () {

            // ボタンの data-item-id 属性から、対象の商品IDを取得
            const itemId = this.dataset.itemId;

            // fetch API を使って「いいね」リクエストを送信
            fetch(`/like/${itemId}`, {
                method: "POST", // HTTPリクエストの種類 (新しくデータを作るのでPOST)
                headers: {
                    "X-CSRF-TOKEN": csrfToken, // CSRFトークンをヘッダーにセット
                    "Content-Type": "application/json", // JSON形式のデータを送信
                },
            })
            .then(response => response.json()) // JSONデータとしてレスポンスを取得
            .then(data => {
                // いいねの数を更新するための要素を取得
                const likeCount = document.getElementById("like-count");

                // いいねされたかどうかの状態をサーバーから取得してボタンを更新
                if (data.liked) {
                    this.classList.add("liked"); // いいね済みスタイルを適用
                    this.innerHTML = "⭐ <span id='like-count'>" + (parseInt(likeCount.innerText) + 1) + "</span>";
                } else {
                    this.classList.remove("liked"); // いいね解除のスタイル
                    this.innerHTML = "☆ <span id='like-count'>" + (parseInt(likeCount.innerText) - 1) + "</span>";
                }
            })
            .catch(() => {
                // エラー発生時の処理を何もしないようにする
            });
        });
    });
});
