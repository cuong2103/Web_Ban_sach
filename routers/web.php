<?php
session_start();
$act = $_GET['act'] ?? '/';


match ($act) {
  // ─── Home mặc định → trang chủ khách hàng ─────────────────────────
  '/' => (new HomeController())->home(),
};