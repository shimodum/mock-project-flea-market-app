document.addEventListener("DOMContentLoaded", function () {
    const paymentSelect = document.getElementById("payment_method");
    const paymentSummary = document.getElementById("payment-summary");
    const purchaseButton = document.getElementById("purchase-button");

    let selectedPaymentMethod = "";

    if (paymentSelect) {
        paymentSelect.addEventListener("change", function () {
            selectedPaymentMethod = this.value;
            if (paymentSummary) {
                paymentSummary.textContent = selectedPaymentMethod;
            }
        });
    }

    if (purchaseButton) {
        purchaseButton.addEventListener("click", function (event) {
            if (!selectedPaymentMethod) {
                event.preventDefault();
                alert("支払い方法を選択してください。");
            }
        });
    }
});
