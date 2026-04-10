<?php
session_start();
$act = $_GET['act'] ?? '/';

// Whitelist các route không cần login
if (!in_array($act, ['/', 'login', 'check-login', 'admin-login', 'check-admin-login', 'logout', 'home', 'register', 'check-register', 'books', 'book-detail', 'about', 'contact'])) {
  checkLogin();
}

match ($act) {
  // ─── Home mặc định → trang chủ khách hàng ─────────────────────────
  '/' => (new HomeController())->home(),

  // ─── Auth ───────────────────────────────────────────────────────
  'login' => (new AuthController())->formLogin(),
  'check-login' => (new AuthController())->login(),
  'register' => (new AuthController())->formRegister(),
  'check-register' => (new AuthController())->register(),
  'admin-login' => (new AuthController())->formAdminLogin(),
  'check-admin-login' => (new AuthController())->adminLogin(),
  'logout' => (new AuthController())->logout(),


  //customer
  'home' => (new HomeController())->home(),
  'books' => (new BookController())->list(),
  'book-detail' => (new BookController())->detail(),
  'about' => (new HomeController())->about(),
  'contact' => (new HomeController())->contact(),

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

  // Orders
  'orders' => (new CartController())->history(),
  'order-detail' => (new CartController())->orderDetail(),
  'order-cancel' => (new CartController())->cancelOrder(),

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

  'admin-categories' => (new AdminCategoryController())->list(),
  'admin-categories-create' => (new AdminCategoryController())->create(),
  'admin-categories-store' => (new AdminCategoryController())->store(),
  'admin-categories-edit' => (new AdminCategoryController())->edit(),
  'admin-categories-update' => (new AdminCategoryController())->update(),
  'admin-categories-delete' => (new AdminCategoryController())->delete(),
  'admin-categories-detail' => (new AdminCategoryController())->detail(),
  // ─── Admin ────────────────────────────────────────────────────────
  'admin-dashboard' => (new DashboardController())->Dashboard(),
  'admin-dashboard-data' => (new DashboardController())->dashboardData(),
  // ─── Admin: User Management ───────────────────────────────────────────────
  'admin-users' => (new AdminUserController())->list(),
  'admin-users-create' => (new AdminUserController())->create(),
  'admin-users-store' => (new AdminUserController())->store(),
  'admin-users-edit' => (new AdminUserController())->edit(),
  'admin-users-update' => (new AdminUserController())->update(),
  'admin-users-toggle-status' => (new AdminUserController())->toggleStatus(),
  // ─── Admin: Inventory Management (View Only) ───────────────────────────────────
  'admin-inventories' => (new InventoryController())->list(),

  // ─── Admin: Flash Sale Management ───────────────────────────────────
  'admin-flash-sales' => (new FlashSaleController())->list(),
  'admin-flash-sales-create' => (new FlashSaleController())->formCreate(),
  'admin-flash-sales-store' => (new FlashSaleController())->create(),
  'admin-flash-sales-edit' => (new FlashSaleController())->formEdit(),
  'admin-flash-sales-update' => (new FlashSaleController())->update(),
  'admin-flash-sales-delete' => (new FlashSaleController())->delete(),
  'admin-flash-sales-add-item' => (new FlashSaleController())->addItem(),
  'admin-flash-sales-remove-item' => (new FlashSaleController())->removeItem(),

  // ─── Admin: Order Management ────────────────────────────────────────────────
  'admin-orders' => (new AdminOrderController())->list(),
  'admin-order-detail' => (new AdminOrderController())->detail(),
  'admin-order-update-status' => (new AdminOrderController())->updateStatus(),

  // ─── Admin: Voucher Management ────────────────────────────────────────────────
  'admin-vouchers' => (new AdminVoucherController())->list(),
  'admin-voucher-add' => (new AdminVoucherController())->create(),
  'admin-voucher-edit' => (new AdminVoucherController())->edit(),
  'admin-voucher-save' => (new AdminVoucherController())->save(),
  'admin-voucher-delete' => (new AdminVoucherController())->delete(),

  // ─── Error pages ──────────────────────────────────────────────────
  '403' => require_once './views/forbidden.php',
  default => require_once './views/notFound.php',
};