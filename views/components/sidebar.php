<?php
$currentAct = $_GET['act'] ?? '';

function isActive($acts, $currentAct)
{
  if (is_array($acts))
    return in_array($currentAct, $acts);
  return $currentAct === $acts;
}

$activeClass = 'bg-green-50 text-green-700 font-semibold';
$inactiveClass = 'text-gray-700 hover:bg-gray-100 font-medium';
?>
<aside class="w-64 bg-white shadow-lg h-screen fixed inset-y-0 left-0 flex flex-col z-50">

  <!-- Logo -->
  <div class="px-6 py-7 border-b border-gray-200">
    <div class="flex items-center space-x-3">
      <div class="relative w-12 h-12">
        <div class="absolute inset-0 bg-[#4CAF50] rounded-2xl"></div>
        <i data-lucide="book-open" class="w-7 h-7 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></i>
      </div>
      <div>
        <h1 class="text-xl font-bold text-gray-900">BookAdmin</h1>
        <p class="text-xs text-gray-500">Quản trị cửa hàng</p>
      </div>
    </div>
  </div>

  <!-- Nav -->
  <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto scrollbar-thin">

    <!-- Dashboard -->
    <a href="<?= BASE_URL ?>?act=admin-dashboard"
      class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors <?= isActive('admin-dashboard', $currentAct) ? $activeClass : $inactiveClass ?>">
      <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
      <span class="whitespace-nowrap">Thống kê</span>
    </a>

    <!-- Quản lý tài khoản -->
    <a href="<?= BASE_URL ?>?act=admin-users"
      class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors <?= isActive(['admin-users', 'admin-users-create', 'admin-users-edit'], $currentAct) ? $activeClass : $inactiveClass ?>">
      <i data-lucide="users" class="w-5 h-5 mr-3"></i>
      <span class="whitespace-nowrap">Quản lí tài khoản</span>
    </a>

  </nav>

  <!-- Bottom: Đăng xuất -->
  <div class="px-4 py-4 border-t border-gray-200">
    <a href="<?= BASE_URL ?>?act=logout"
      class="flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
      <i data-lucide="log-out" class="w-5 h-5 mr-3 text-red-500"></i>
      <span>Đăng xuất</span>
    </a>
  </div>
</aside>