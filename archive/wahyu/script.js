function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
    });

    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}
function updateSubtotal() {
    const pricePerItem = 8888;
    let quantityInput = document.getElementById('quantity');
    let subtotalElement = document.getElementById('subtotal');

    let quantity = parseInt(quantityInput.value);
    let subtotal = pricePerItem * quantity;

    subtotalElement.innerText = `Subtotal: Rp${subtotal.toLocaleString('id-ID')}`;
}
document.addEventListener("DOMContentLoaded", function () {
    let quantityInput = document.getElementById('quantity');
    let decreaseBtn = document.getElementById('decrease');
    let increaseBtn = document.getElementById('increase');

    quantityInput.addEventListener('input', updateSubtotal);
    decreaseBtn.addEventListener('click', function () {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            updateSubtotal();
        }
    });
    increaseBtn.addEventListener('click', function () {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue < 4800) {
            quantityInput.value = currentValue + 1;
            updateSubtotal();
        }
    });
    updateSubtotal();
});
