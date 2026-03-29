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


  //customer
  'home' => (new HomeController())->home(),
  'books' => (new BookController())->list(),
  'book-detail' => (new BookController())->detail(),

  // Cart
  'cart' => (new CartController())->view(),
  'cart-add' => (new CartController())->add(),
  'cart-remove' => (new CartController())->remove(),

  // Orders
  'checkout' => (new OrderController())->checkout(),
  'create-order' => (new OrderController())->create(),
  'orders' => (new OrderController())->index(),
  'order-detail' => (new OrderController())->detail(),

  // Trang không tìm thấy cho mọi route không khớp
  default => require_once './views/notFound.php',
};