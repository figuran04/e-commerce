<?php
function formatTerjual($jumlah)
{
  if ($jumlah >= 1_000_000) return round($jumlah / 1_000_000, 1) . 'jt+';
  if ($jumlah >= 1_000) return round($jumlah / 1_000, 1) . 'rb+';
  return $jumlah;
}
