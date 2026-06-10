<?php
require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . '/../../helpers/status.php';
require_once __DIR__ . '/../../includes/order_actions.php';
$pageTitle = "Pesanan Saya";
ob_start();
?>

<div class="space-y-6">
  <div class="flex flex-col justify-between md:items-center md:flex-row">
    <h2 class="flex items-center gap-2 text-xl font-bold"><i class="ph-fill ph-package"></i> Pesanan Saya</h2>
    <a href="../orders/store_orders.php" class="text-right hover:underline hover:text-lime-700">ke pesanan toko <i class="ph ph-arrow-right"></i></a>
  </div>

  <?php include '../partials/alerts.php'; ?>

  <!-- Filter Tab Status -->
  <div id="statusTabs" class="flex gap-4 overflow-x-auto text-sm border-b border-b-gray-200 custom-scroll">
    <?php
    $statuses = ['Semua','Dipesan','Dikirim','Selesai','Dibatalkan','Ditolak'];
    $activeStatus = $_GET['status'] ?? 'Semua';
    foreach ($statuses as $s) {
      $cls = $s === $activeStatus ? 'border-b-[3px] border-lime-600 text-lime-600 font-semibold' : 'text-gray-500';
      echo "<a href='?status=$s' class='pb-2 $cls' data-status='$s'>$s</a>";
    }
    ?>
  </div>

  <!-- State: Loading -->
  <div id="ordersLoading" class="py-10 text-center text-gray-400">
    <i class="text-4xl ph ph-spinner animate-spin"></i>
    <p class="mt-2">Memuat pesanan...</p>
  </div>

  <!-- State: Belum Login -->
  <div id="ordersNotLogin" class="py-10 text-center text-gray-500 hidden">
    <i class="mb-2 text-5xl ph ph-lock"></i>
    <p class="text-lg font-medium">Anda belum masuk.</p>
    <a href="../login" class="inline-block px-4 py-2 mt-4 text-white rounded bg-lime-600 hover:bg-lime-700">Masuk Sekarang</a>
  </div>

  <!-- State: Kosong -->
  <div id="ordersEmpty" class="py-10 text-center text-gray-500 hidden">
    <i class="mb-2 text-5xl ph ph-truck"></i>
    <p class="text-lg font-medium">Belum ada pesanan.</p>
    <a href="../products" class="inline-block px-4 py-2 mt-4 text-white rounded bg-lime-600 hover:bg-lime-700">Belanja Sekarang</a>
  </div>

  <!-- Daftar Pesanan -->
  <div id="ordersList" class="space-y-4 hidden"></div>
</div>

<script>
const BASE     = '';
const BASE_URL = BASE + '/views';

const STATUS_COLORS = {
  'Dipesan':    'yellow-200',
  'Dikirim':    'blue-200',
  'Selesai':    'green-200',
  'Dibatalkan': 'gray-200',
  'Ditolak':    'red-200',
};

function formatRp(n) { return 'Rp' + Number(n).toLocaleString('id-ID'); }
function formatDate(d) {
  return new Date(d).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'});
}

function getStatusColor(s) { return STATUS_COLORS[s] || 'gray-100'; }

function renderOrders(orders, filterStatus) {
  const list = document.getElementById('ordersList');
  list.innerHTML = '';

  const filtered = filterStatus === 'Semua' ? orders : orders.filter(o => o.status === filterStatus);
  if (filtered.length === 0) {
    document.getElementById('ordersEmpty').classList.remove('hidden');
    return;
  }

  document.getElementById('ordersEmpty').classList.add('hidden');
  list.classList.remove('hidden');

  filtered.forEach(order => {
    const color     = getStatusColor(order.status);
    const itemsHtml = (order.items || []).map(item => `
      <a href="${BASE}/views/product_detail/?id=${item.product_id}" class="transition-all rounded-lg hover:bg-gray-100 group">
        <div class="flex items-center gap-3 p-1">
          <img src="${BASE}/uploads/${item.image}" class="object-cover w-16 h-16 rounded-lg group-hover:scale-105">
          <div>
            <p class="font-medium line-clamp-2">${item.name}</p>
            <p class="text-sm text-gray-500">Qty: ${item.quantity} x ${formatRp(item.price)}</p>
          </div>
        </div>
      </a>`).join('');

    const phone = (order.store_phone || '').replace(/[^0-9]/g,'').replace(/^0/, '62');
    const waMsg = encodeURIComponent(`Halo *${order.store_name}*, saya ingin menanyakan pesanan #${order.id} (Status: ${order.status})`);
    const waLink = phone ? `https://wa.me/${phone}?text=${waMsg}` : null;

    list.innerHTML += `
      <div class="p-4 border-[3px] rounded-lg shadow-sm border-${color} bg-white">
        <div class="flex items-start justify-between mb-2 text-sm">
          <div>
            <p>ID Pesanan: <a href="${BASE_URL}/orders/?id=${order.id}" class="hover:underline text-lime-600">${order.id}</a></p>
            ${order.store_name ? `<p class="text-gray-500">Toko: <a href="${BASE_URL}/store/?id=${order.store_id}" class="hover:underline text-lime-600">${order.store_name}</a></p>` : ''}
            <p class="text-gray-500">Tanggal: ${formatDate(order.order_date)}</p>
          </div>
          <span class="px-2 py-1 text-xs text-gray-700 bg-${color} rounded">${order.status}</span>
        </div>
        <div class="grid gap-4 md:grid-cols-2">${itemsHtml}</div>
        <div class="flex items-center justify-between mt-4">
          <p class="font-semibold text-lime-600">Total: ${formatRp(order.total_price)}</p>
        </div>
        ${waLink ? `<a href="${waLink}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 mt-2 text-sm border text-green-600 hover:text-green-700 border-green-600 rounded hover:border-green-700"><i class="ph ph-whatsapp-logo"></i> Hubungi Penjual</a>` : ''}
      </div>`;
  });
}

async function loadOrders() {
  const token = Auth.getToken();
  if (!token) {
    document.getElementById('ordersLoading').classList.add('hidden');
    document.getElementById('ordersNotLogin').classList.remove('hidden');
    return;
  }

  try {
    const res    = await Auth.apiFetch('orders');
    if (!res) return;
    const data   = await res.json();
    const orders = data.data || [];

    document.getElementById('ordersLoading').classList.add('hidden');

    if (orders.length === 0) {
      document.getElementById('ordersEmpty').classList.remove('hidden');
      return;
    }

    // Ambil status dari URL param
    const urlParams    = new URLSearchParams(window.location.search);
    const activeStatus = urlParams.get('status') || 'Semua';
    renderOrders(orders, activeStatus);

    // Bind tab click untuk filter tanpa reload
    document.querySelectorAll('#statusTabs a').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const st = link.dataset.status;
        document.querySelectorAll('#statusTabs a').forEach(l => {
          l.className = 'pb-2 text-gray-500';
        });
        link.className = 'pb-2 border-b-[3px] border-lime-600 text-lime-600 font-semibold';
        renderOrders(orders, st);
        history.pushState(null, '', '?status=' + st);
      });
    });

  } catch(e) {
    document.getElementById('ordersLoading').innerHTML = '<p class="text-red-500">Gagal memuat pesanan.</p>';
  }
}

document.addEventListener('DOMContentLoaded', loadOrders);
</script>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
