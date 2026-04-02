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

  // profile customer
  'profile' => (new AuthController())->profile(),
  'profile-update' => (new AuthController())->updateProfile(),
  'profile-password' => (new AuthController())->updatePassword(),
  
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

  // ================================
  // THÊM ROUTES MỚI Ở ĐÂY
  // ================================
  'admin-books' => (new AdminBookController())->list(),
  'admin-books-create' => (new AdminBookController())->create(),
  'admin-books-store' => (new AdminBookController())->store(),
  'admin-books-edit' => (new AdminBookController())->edit(),
  'admin-books-update' => (new AdminBookController())->update(),
  'admin-books-delete' => (new AdminBookController())->delete(),
  'admin-books-detail' => (new AdminBookController())->detail(),
  'admin-books-toggle-status' => (new AdminBookController())->toggleStatus(),
};