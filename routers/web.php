<?php
session_start();
$act = $_GET['act'] ?? '/';

match ($act) {
  // ─── Home mặc định → trang chủ khách hàng ─────────────────────────
  '/' => (new HomeController())->home(),

  // ─── Auth ───────────────────────────────────────────────────────
  'login' => (new AuthController())->formLogin(),
  'check-login' => (new AuthController())->login(),
  'register' => (new AuthController())->formRegister(),
  'check-register' => (new AuthController())->register(),
  'logout' => (new AuthController())->logout(),
};