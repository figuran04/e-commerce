<?php
// Base URL — can be overridden by APP_URL env var when running in Docker
$BASE     = getenv('APP_URL') ?: 'http://localhost:8081';
$BASE_URL = rtrim($BASE, '/') . '/views';

