document.addEventListener("DOMContentLoaded", function () {
    const paymentSelect = document.getElementById("payment_method");
    const purchaseButton = document.getElementById("purchase-button");

    let selectedPaymentMethod = "";

    if (paymentSelect) {
        paymentSelect.addEventListener("change", function () {
            selectedPaymentMethod = this.value;
        });
    }

    if (purchaseButton) {
        purchaseButton.addEventListener("click", function (event) {
            event.preventDefault();

            if (!selectedPaymentMethod) {
                alert("支払い方法を選択してください。");
                return;
            }

            // Stripe の決済ページへリダイレクト
            fetch("/stripe/checkout", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    item_id: this.dataset.itemId,
                    payment_method: selectedPaymentMethod  // 支払い方法を追加
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.url) {
                    window.location.href = data.url;
                } else {
                    alert("決済ページへのリダイレクトに失敗しました。");
                }
            })
            .catch(error => {
                console.error("決済処理でエラーが発生しました:", error);
            });
        });
    }
});
