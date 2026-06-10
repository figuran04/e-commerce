<?php
function getStatusColor($status)
{
  return match ($status) {
    'Selesai' => 'green-100',
    'Dibatalkan', 'Ditolak' => 'red-100',
    default => 'gray-100',
  };
}
