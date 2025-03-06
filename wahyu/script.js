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

// Fungsi untuk update harga subtotal berdasarkan jumlah produk
function updateSubtotal() {
    const pricePerItem = 8888; // Harga satuan Rp8.888
    let quantityInput = document.getElementById('quantity');
    let subtotalElement = document.getElementById('subtotal');

    let quantity = parseInt(quantityInput.value);
    let subtotal = pricePerItem * quantity;

    subtotalElement.innerText = `Subtotal: Rp${subtotal.toLocaleString('id-ID')}`;
}

// Menambah event listener setelah halaman dimuat
document.addEventListener("DOMContentLoaded", function () {
    let quantityInput = document.getElementById('quantity');
    let decreaseBtn = document.getElementById('decrease');
    let increaseBtn = document.getElementById('increase');

    // Update subtotal saat input jumlah berubah
    quantityInput.addEventListener('input', updateSubtotal);

    // Tombol -
    decreaseBtn.addEventListener('click', function () {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            updateSubtotal();
        }
    });

    // Tombol +
    increaseBtn.addEventListener('click', function () {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue < 4800) {
            quantityInput.value = currentValue + 1;
            updateSubtotal();
        }
    });

    // Pastikan harga awal sesuai dengan jumlah default
    updateSubtotal();
});
