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
  'cart' => (new CartController())->index(),
  'cart-add' => (new CartController())->add(),
  'cart-update' => (new CartController())->update(),
  'cart-remove' => (new CartController())->remove(),
  'cart-apply-voucher' => (new CartController())->applyVoucher(),
  'cart-clear-voucher' => (new CartController())->clearVoucher(),
  'checkout' => (new CartController())->checkout(),
  'checkout-place' => (new CartController())->placeOrder(),
  'checkout-success' => (new CartController())->success(),
};