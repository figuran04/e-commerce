<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
  session_start();
}
require __DIR__ . '/database.php';
