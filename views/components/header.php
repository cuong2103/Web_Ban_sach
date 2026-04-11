<?php
$currentUser = $_SESSION['currentUser'] ?? null;
$fullname = $currentUser['fullname'] ?? 'Admin';
$role     = ($currentUser['roles'] ?? '') == 1 ? 'Quản trị viên' : 'Nhân viên';
$avatar   = strtoupper(mb_substr($fullname, 0, 1));
$userId   = $currentUser['id'] ?? null;
?>

<!DOCTYPE html>
<html lang="vi" class="bg-[#F5F5F5]">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BookAdmin – Quản trị hệ thống</title>
  <script>
      const originalWarn = console.warn;
      console.warn = (...args) => {
          if (args[0] && typeof args[0] === 'string' && args[0].includes('cdn.tailwindcss.com should not be used in production')) return;
          originalWarn.apply(console, args);
      };
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="<?= BASE_URL ?>assets/common.js"></script>
</head>

<body class="bg-[#F5F5F5] text-gray-900 font-sans antialiased min-h-screen flex overflow-x-hidden">

  <!-- Sidebar được include riêng trong mỗi view -->

  <!-- Main wrapper (ml-64 = độ rộng sidebar) -->
  <div class="flex-1 ml-64 flex flex-col min-h-screen w-[calc(100%-16rem)]">

    <!-- Top Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
      <div id="alert-message"
        class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg opacity-0 transition-opacity duration-500 z-50">
      </div>
      <div class="px-6 py-5 flex items-center justify-end">
        <div class="flex items-center space-x-4 pl-4">
          <!-- User info -->
          <div class="flex items-center gap-3">
            <a class="flex items-center gap-3" href="<?= BASE_URL ?>?act=admin-profile">
              <div class="w-9 h-9 bg-[#4CAF50] rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-sm">
                <?= $avatar ?>
              </div>
              <div class="hidden sm:block">
                <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($fullname) ?></p>
                <p class="text-xs text-gray-500"><?= $role ?></p>
              </div>
            </a>
          </div>
        </div>
      </div>
    </header>
