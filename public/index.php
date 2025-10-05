<?php

session_name('RADARPETSESSID');
session_set_cookie_params([
  'lifetime' => 0,
  'path' => '/',
  'domain' => '',
  'secure' => false,
  'httponly' => true,
  'samesite' => 'Lax'
]);

require __DIR__ . '/../config/bootstrap.php';
