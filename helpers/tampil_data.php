<?php
function tampilkanData($tampil_data, $fallback = 'Belum diisi')
{
  return !empty($tampil_data) ? htmlspecialchars($tampil_data) : $fallback;
}
