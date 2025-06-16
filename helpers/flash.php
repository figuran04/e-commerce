<?php
function setFlash(string $type, string $message): void
{
  $_SESSION['flash'][$type] = $message;
}

function getFlash(string $type): ?string
{
  if (isset($_SESSION['flash'][$type])) {
    $message = $_SESSION['flash'][$type];
    unset($_SESSION['flash'][$type]); // Hilangkan setelah dibaca
    return $message;
  }
  return null;
}
