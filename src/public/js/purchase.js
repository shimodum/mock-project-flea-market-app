document.addEventListener("DOMContentLoaded", function () {
    const paymentSelect = document.getElementById("payment_method");
    const paymentSummary = document.getElementById("payment-summary");
    const purchaseButton = document.getElementById("purchase-button");

    // 初期選択状態の保持
    let selectedPaymentMethod = "";

    if (paymentSelect) {
        paymentSelect.addEventListener("change", function () {
            selectedPaymentMethod = this.value;
            if (paymentSummary) {
                paymentSummary.textContent = selectedPaymentMethod; // 小計エリアの支払い方法を更新
            }
        });
    }

    if (purchaseButton) {
        purchaseButton.addEventListener("click", function (event) {
            event.preventDefault(); // 通常のフォーム送信を防ぐ

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
                    payment_method: selectedPaymentMethod
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
            .catch(error => console.error("決済処理でエラーが発生しました:", error));
        });
    }
});
