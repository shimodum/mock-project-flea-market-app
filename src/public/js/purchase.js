document.addEventListener("DOMContentLoaded", function () {
    const paymentSelect = document.getElementById("payment_method");
    const paymentSummary = document.getElementById("payment-summary");
    const purchaseButton = document.getElementById("purchase-button");

    let selectedPaymentMethod = "";

    const paymentLabels = {
        "1": "コンビニ支払い",
        "2": "カード支払い"
    };

    if (paymentSelect) {
        paymentSelect.addEventListener("change", function () {
            selectedPaymentMethod = this.value;
            paymentSummary.textContent = paymentLabels[selectedPaymentMethod] || "未選択"; // 選択内容を日本語で注文内容に反映
        });
    }

    if (purchaseButton) {
        purchaseButton.addEventListener("click", function (event) {
            event.preventDefault();

            if (!selectedPaymentMethod) {
                alert("支払い方法を選択してください。");
                return;
            }

            const itemId = this.dataset.itemId;

            // コンビニ支払い（value = "1"）なら通常のフォーム送信を行う
            if (selectedPaymentMethod === "1") {
                const form = document.getElementById("purchase-form");
                form.action = `/purchase/${itemId}`;
                form.submit();
            }

            // カード支払い（value = "2"）ならStripe の決済ページへリダイレクト
            else if (selectedPaymentMethod === "2") {
                fetch("/stripe/checkout", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        payment_method: selectedPaymentMethod // 支払い方法を送信
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
            }
        });
    }
});
