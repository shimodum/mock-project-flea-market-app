document.addEventListener("DOMContentLoaded", function () {
    const likeButtons = document.querySelectorAll(".like-button");

    likeButtons.forEach(button => {
        button.addEventListener("click", function () {
            const itemId = this.dataset.itemId;
            const likeCountElement = document.getElementById(`like-count-${itemId}`);

            fetch(`/like/${itemId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 401) {
                        alert("いいねするにはログインが必要です。");
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (!likeCountElement) {
                    console.error(`Like count element not found for item ${itemId}`);
                    return;
                }

                if (data.liked) {
                    this.classList.add("liked");
                    this.innerHTML = `⭐ <span id='like-count-${itemId}'>${data.likeCount}</span>`;
                } else {
                    this.classList.remove("liked");
                    this.innerHTML = `☆ <span id='like-count-${itemId}'>${data.likeCount}</span>`;
                }
            })
            .catch(error => {
                console.error("いいねの送信中にエラーが発生しました:", error);
            });
        });
    });
});
