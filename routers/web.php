<?php
session_start();
$act = $_GET['act'] ?? '/';


match ($act) {
  // ─── Home mặc định → trang chủ khách hàng ─────────────────────────
  '/' => (new HomeController())->home(),


  //customer
  'home' => (new HomeController())->home(),
  'books' => (new BookController())->list(),
  'book-detail' => (new BookController())->detail(),
};