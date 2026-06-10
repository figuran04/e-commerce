<?php
require_once __DIR__ . '/../../config/init.php';
$pageTitle = "Keranjang";
ob_start();
?>

<div class="space-y-6">
  <h2 class="flex items-center gap-2 text-xl font-bold"><i class="ph-fill ph-shopping-cart"></i> Keranjang Belanja</h2>
  <div id="cartAlert"></div>

  <!-- State: Loading -->
  <div id="cartLoading" class="py-10 text-center text-gray-400">
    <i class="text-4xl ph ph-spinner animate-spin"></i>
    <p class="mt-2">Memuat keranjang...</p>
  </div>

  <!-- State: Belum login -->
  <div id="cartNotLogin" class="py-10 text-center text-gray-500 hidden">
    <i class="mb-2 text-5xl ph ph-lock"></i>
    <p class="text-lg font-medium">Anda belum masuk.</p>
    <a href="../login" class="inline-block px-4 py-2 mt-4 text-white rounded bg-lime-600 hover:bg-lime-700">Masuk Sekarang</a>
  </div>

  <!-- State: Kosong -->
  <div id="cartEmpty" class="py-10 text-center text-gray-500 hidden">
    <i class="mb-2 text-5xl ph ph-shopping-cart"></i>
    <p class="text-lg font-medium">Keranjang belanja Anda kosong.</p>
    <a href="../products" class="inline-block px-4 py-2 mt-4 text-white rounded bg-lime-600 hover:bg-lime-700">Mulai Belanja</a>
  </div>

  <!-- State: Isi Keranjang -->
  <div id="cartContent" class="hidden">
    <div class="text-right w-full mb-2">
      <button type="button" id="deleteSelectedBtn" onclick="deleteSelected()" class="hidden text-red-600 hover:underline">
        <i class="ph ph-trash"></i> Hapus
      </button>
    </div>
    <div id="cartStoreGroups" class="space-y-6"></div>
    <div class="flex flex-col items-center justify-between gap-2 mt-6 md:flex-row">
      <div class="flex gap-3 justify-between w-full">
        <div class="flex items-center gap-2">
          <input type="checkbox" id="selectAll" class="w-4 h-4" onchange="toggleSelectAll(this)">
          <label for="selectAll" class="font-medium">Semua</label>
        </div>
        <div class="flex gap-2 items-center">
          <p class="text-sm font-semibold">Total:</p>
          <p id="selectedTotal" class="font-bold text-lime-700">Rp0</p>
          <button onclick="checkout()" class="px-6 py-2 text-sm text-white rounded bg-lime-600 hover:bg-lime-700">
            <i class="ph-bold ph-check"></i> Checkout
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const BASE = '';
let cartItems = [];

// ─── Render ───────────────────────────────────────────────────────────────────
function formatRp(num) {
  return 'Rp' + Number(num).toLocaleString('id-ID');
}

function renderCart(items) {
  cartItems = items;
  const groups = {};
  items.forEach(item => {
    const key = item.store_name;
    if (!groups[key]) groups[key] = { store_id: item.store_id, items: [] };
    groups[key].items.push(item);
  });

  const container = document.getElementById('cartStoreGroups');
  container.innerHTML = '';

  Object.entries(groups).forEach(([storeName, group]) => {
    const storeKey = storeName.replace(/\s/g,'_');
    const rows = group.items.map(cart => {
      const subtotal = cart.price * cart.quantity;
      const stockWarn = cart.stock === 0
        ? `<span class="text-xs text-red-600 ml-2">Stok habis!</span>`
        : cart.quantity > cart.stock
        ? `<span class="text-xs text-red-600 ml-2">Stok tidak mencukupi!</span>`
        : cart.stock <= 5
        ? `<span class="text-xs text-yellow-600 ml-2">Stok hampir habis</span>`
        : '';
      return `
        <tr>
          <td class="p-3">
            <input type="checkbox" class="w-4 h-4 item-checkbox"
              value="${cart.cart_id}"
              data-store="${storeName}"
              data-subtotal="${subtotal}"
              data-price="${cart.price}"
              ${cart.stock === 0 || cart.quantity > cart.stock ? 'disabled' : ''}
              onchange="updateSelectedTotal()">
          </td>
          <td class="p-3 min-w-40"><a href="${BASE}/views/product_detail/?id=${cart.product_id}" class="font-medium hover:underline line-clamp-3">${cart.name}</a></td>
          <td class="p-3"><img src="${BASE}/uploads/${cart.image}" class="object-cover rounded w-14 h-14"></td>
          <td class="p-3">${formatRp(cart.price)}</td>
          <td class="p-3">
            <input type="number" value="${Math.min(cart.quantity, cart.stock)}" min="1" max="${cart.stock}"
              class="w-16 text-center border border-gray-200 rounded"
              data-cart-id="${cart.cart_id}" data-price="${cart.price}"
              onchange="updateCartQty(this)"
              ${cart.stock === 0 ? 'disabled' : ''}>
          </td>
          <td class="p-3" id="subtotal-${cart.cart_id}">${formatRp(subtotal)}</td>
          <td class="p-3 text-sm ${cart.stock < cart.quantity ? 'text-red-600' : 'text-gray-600'}">${cart.stock}${stockWarn}</td>
          <td class="p-3 text-sm">
            <a href="javascript:void(0)" onclick="removeItem(${cart.cart_id})" class="text-red-600 hover:underline">Hapus</a>
          </td>
        </tr>`;
    }).join('');

    container.innerHTML += `
      <div class="p-4 mb-6 border border-gray-200 rounded-lg bg-white">
        <div class="flex items-center gap-2 mb-3">
          <input type="checkbox" class="w-4 h-4 store-checkbox" data-store="${storeName}" onchange="toggleStoreItems(this)">
          <label class="text-lg font-semibold"><a href="${BASE}/views/store/?id=${group.store_id}" class="hover:underline">${storeName}</a></label>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm text-left">
            <thead class="text-gray-700 bg-gray-100">
              <tr>
                <th class="p-3"></th><th class="p-3">Nama</th><th class="p-3">Gambar</th>
                <th class="p-3">Harga</th><th class="p-3">Jumlah</th>
                <th class="p-3">Subtotal</th><th class="p-3">Stok</th><th class="p-3">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y">${rows}</tbody>
          </table>
        </div>
      </div>`;
  });
}

// ─── Load Cart dari API ───────────────────────────────────────────────────────
async function loadCart() {
  const token = Auth.getToken();
  if (!token) {
    document.getElementById('cartLoading').classList.add('hidden');
    document.getElementById('cartNotLogin').classList.remove('hidden');
    return;
  }

  try {
    const res  = await Auth.apiFetch('cart');
    if (!res) return;
    const data = await res.json();

    document.getElementById('cartLoading').classList.add('hidden');

    if (data.data.item_count === 0) {
      document.getElementById('cartEmpty').classList.remove('hidden');
    } else {
      document.getElementById('cartContent').classList.remove('hidden');
      renderCart(data.data.items);
    }
  } catch(e) {
    document.getElementById('cartLoading').innerHTML = '<p class="text-red-500">Gagal memuat keranjang.</p>';
  }
}

// ─── Update Quantity ─────────────────────────────────────────────────────────
async function updateCartQty(input) {
  const cartId   = input.dataset.cartId;
  const quantity = parseInt(input.value);
  const price    = parseFloat(input.dataset.price);
  document.getElementById(`subtotal-${cartId}`).textContent = formatRp(price * quantity);

  const cb = document.querySelector(`.item-checkbox[value="${cartId}"]`);
  if (cb) { cb.dataset.subtotal = price * quantity; updateSelectedTotal(); }

  await Auth.apiFetch('cart', {
    method: 'POST',
    body: JSON.stringify({ product_id: cartId, quantity })  // gateway cart POST
  });
  // Update quantity via legacy controller (masih kompatibel)
  await fetch(`${BASE}/controllers/cart/update_cart.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `cart_id=${cartId}&quantity=${quantity}`
  });
}

// ─── Hapus Item ───────────────────────────────────────────────────────────────
async function removeItem(cartId) {
  if (!confirm('Hapus produk dari keranjang?')) return;
  const res  = await Auth.apiFetch('cart', {
    method: 'DELETE',
    body: JSON.stringify({ cart_id: cartId })
  });
  const data = await res.json();
  if (data.status === 'success') loadCart();
}

// ─── Hapus Terpilih ───────────────────────────────────────────────────────────
async function deleteSelected() {
  const checked = [...document.querySelectorAll('.item-checkbox:checked')];
  if (checked.length === 0) { alert('Pilih item dulu.'); return; }
  if (!confirm('Hapus item yang dipilih?')) return;
  for (const cb of checked) {
    await Auth.apiFetch('cart', { method: 'DELETE', body: JSON.stringify({ cart_id: cb.value }) });
  }
  loadCart();
}

// ─── Checkout ────────────────────────────────────────────────────────────────
async function checkout() {
  const checked = [...document.querySelectorAll('.item-checkbox:checked')];
  if (checked.length === 0) { alert('Pilih setidaknya satu produk untuk checkout.'); return; }

  // Pastikan session PHP sudah tersinkronisasi sebelum submit form
  const btn = document.querySelector('button[onclick="checkout()"]');
  const originalText = btn ? btn.innerHTML : '';
  if (btn) { btn.disabled = true; btn.innerHTML = '<i class="ph ph-spinner animate-spin"></i> Memproses...'; }

  try {
    if (typeof Auth !== 'undefined' && Auth.getToken()) {
      await Auth.syncSession();
    }
  } catch(e) {
    console.warn('syncSession sebelum checkout gagal:', e);
  }

  const selectedIds = checked.map(cb => cb.value);
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '../checkout/index.php';
  selectedIds.forEach(id => {
    const inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = 'selected_items[]'; inp.value = id;
    form.appendChild(inp);
  });
  const act = document.createElement('input');
  act.type = 'hidden'; act.name = 'action'; act.value = 'checkout_selected';
  form.appendChild(act);
  document.body.appendChild(form);
  form.submit();
}

// ─── Checkbox Helpers ─────────────────────────────────────────────────────────
function updateSelectedTotal() {
  let total = 0;
  document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
    total += parseFloat(cb.dataset.subtotal) || 0;
  });
  document.getElementById('selectedTotal').textContent = formatRp(total);
  const anyChecked = document.querySelectorAll('.item-checkbox:checked').length > 0;
  document.getElementById('deleteSelectedBtn').classList.toggle('hidden', !anyChecked);
}

function toggleSelectAll(cb) {
  document.querySelectorAll('.store-checkbox, .item-checkbox').forEach(el => el.checked = cb.checked);
  updateSelectedTotal();
}

function toggleStoreItems(storeCb) {
  document.querySelectorAll(`.item-checkbox[data-store="${storeCb.dataset.store}"]`).forEach(el => el.checked = storeCb.checked);
  updateSelectedTotal();
}

document.addEventListener('DOMContentLoaded', loadCart);
</script>

<?php
$content = ob_get_clean();
include '../../layout.php';
