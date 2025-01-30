document.addEventListener("DOMContentLoaded", function () {
    const likeButtons = document.querySelectorAll(".like-button");
    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');

    if (!csrfTokenElement) {
        console.error("CSRF token not found.");
        return;
    }

    const csrfToken = csrfTokenElement.content;

    likeButtons.forEach(button => {
        button.addEventListener("click", function () {
            const itemId = this.dataset.itemId;

            fetch(`/like/${itemId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                },
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 401) {
                        alert("ログインが必要です。ログインページへ移動します。");
                        window.location.href = "/login";
                    } else {
                        throw new Error("サーバーエラーが発生しました。");
                    }
                }
                return response.json();
            })
            .then(data => {
                const likeCount = document.getElementById("like-count");

                if (data.liked) {
                    this.classList.add("liked");
                    this.innerHTML = "⭐ <span id='like-count'>" + (parseInt(likeCount.innerText) + 1) + "</span>";
                } else {
                    this.classList.remove("liked");
                    this.innerHTML = "☆ <span id='like-count'>" + (parseInt(likeCount.innerText) - 1) + "</span>";
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("通信エラーが発生しました。再度お試しください。");
            });
        });
    });
});
