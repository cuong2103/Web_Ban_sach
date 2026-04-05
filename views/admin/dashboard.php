<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-y-auto p-5 space-y-6">

  <!-- Page title -->
  <div>
    <h1 class="text-xl font-bold text-[#333]">Dashboard</h1>
    <p class="text-sm text-gray-400">Tổng quan hệ thống – <?= date('d/m/Y') ?></p>
  </div>

  <!-- Stats cards -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-green-100 text-[#4CAF50]">
          <i data-lucide="trending-up" class="w-5 h-5"></i>
        </div>
        <span class="flex items-center gap-1 text-xs font-medium text-[#4CAF50]">
          <i data-lucide="arrow-up-right" class="w-3 h-3"></i>+12.5%
        </span>
      </div>
      <p class="text-xs text-gray-400 mb-1">Tổng doanh thu</p>
      <p class="font-bold text-[#333]" id="totalRevenueValue"><?= number_format($stats['total_revenue'], 0, ',', '.') ?> ₫</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-100 text-blue-600">
          <i data-lucide="shopping-bag" class="w-5 h-5"></i>
        </div>
        <span class="flex items-center gap-1 text-xs font-medium text-[#4CAF50]">
          <i data-lucide="arrow-up-right" class="w-3 h-3"></i>+8.3%
        </span>
      </div>
      <p class="text-xs text-gray-400 mb-1">Tổng đơn hàng</p>
      <p class="font-bold text-[#333]" id="totalOrdersValue"><?= $stats['total_orders'] ?></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-yellow-100 text-yellow-600">
          <i data-lucide="book-open" class="w-5 h-5"></i>
        </div>
        <span class="flex items-center gap-1 text-xs font-medium text-red-500">
          <i data-lucide="arrow-down-right" class="w-3 h-3"></i>-2.1%
        </span>
      </div>
      <p class="text-xs text-gray-400 mb-1">Sách trong kho</p>
      <p class="font-bold text-[#333]" id="totalBooksValue"><?= $stats['total_books'] ?></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-purple-100 text-purple-600">
          <i data-lucide="users" class="w-5 h-5"></i>
        </div>
        <span class="flex items-center gap-1 text-xs font-medium text-[#4CAF50]">
          <i data-lucide="arrow-up-right" class="w-3 h-3"></i>+15.2%
        </span>
      </div>
      <p class="text-xs text-gray-400 mb-1">Tổng khách hàng</p>
      <p class="font-bold text-[#333]" id="totalCustomersValue"><?= $stats['total_customers'] ?></p>
    </div>
  </div>

  <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-yellow-100 text-yellow-600">
          <i data-lucide="clock" class="w-5 h-5"></i>
        </div>
      </div>
      <p class="text-xs text-gray-400 mb-1">Đơn chờ xử lý</p>
      <p class="font-bold text-[#333]" id="pendingOrdersValue"><?= $stats['pending_orders'] ?></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-green-100 text-[#4CAF50]">
          <i data-lucide="dollar-sign" class="w-5 h-5"></i>
        </div>
      </div>
      <p class="text-xs text-gray-400 mb-1">Doanh thu hôm nay</p>
      <p class="font-bold text-[#333]" id="revenueTodayValue"><?= number_format($stats['revenue_today'], 0, ',', '.') ?> ₫</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-100 text-blue-600">
          <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
        </div>
      </div>
      <p class="text-xs text-gray-400 mb-1">Giá trị đơn trung bình</p>
      <p class="font-bold text-[#333]" id="averageOrderValue"><?= number_format($stats['average_order_value'], 0, ',', '.') ?> ₫</p>
    </div>
  </div>

  <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-orange-100 text-orange-600">
          <i data-lucide="calendar" class="w-5 h-5"></i>
        </div>
      </div>
      <p class="text-xs text-gray-400 mb-1">Đơn hôm nay</p>
      <p class="font-bold text-[#333]" id="todayOrdersValue"><?= $stats['today_orders'] ?></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-100 text-blue-600">
          <i data-lucide="user-plus" class="w-5 h-5"></i>
        </div>
      </div>
      <p class="text-xs text-gray-400 mb-1">Khách hàng mới tháng</p>
      <p class="font-bold text-[#333]" id="newCustomersValue"><?= $stats['new_customers'] ?></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
      <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-purple-100 text-purple-600">
          <i data-lucide="users" class="w-5 h-5"></i>
        </div>
      </div>
      <p class="text-xs text-gray-400 mb-1">Top khách hàng</p>
      <p class="font-bold text-[#333]">5 người</p>
    </div>
  </div>

  <!-- Revenue Chart -->
  <div class="bg-white rounded-xl shadow-sm p-5">
    <h3 class="font-semibold text-[#333] text-sm mb-4">Doanh thu theo tháng</h3>
    <div id="revenueChartContainer">
      <canvas id="revenueChart" width="400" height="200"></canvas>
    </div>
    <div id="revenueChartEmpty" class="hidden text-sm text-gray-500">Chưa có dữ liệu doanh thu phù hợp để hiển thị biểu đồ.</div>
  </div>

  <!-- Order status distribution -->
  <div class="grid grid-cols-2 xl:grid-cols-5 gap-4">
    <div class="bg-white rounded-xl shadow-sm p-5">
      <p class="text-xs text-gray-400 mb-2">Đơn chờ xử lý</p>
      <p class="font-bold text-[#333] text-lg" id="statusPendingValue"><?= $stats['order_status_distribution']['Pending'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
      <p class="text-xs text-gray-400 mb-2">Đã xác nhận</p>
      <p class="font-bold text-[#333] text-lg" id="statusConfirmedValue"><?= $stats['order_status_distribution']['Confirmed'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
      <p class="text-xs text-gray-400 mb-2">Đang giao</p>
      <p class="font-bold text-[#333] text-lg" id="statusShippingValue"><?= $stats['order_status_distribution']['Shipping'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
      <p class="text-xs text-gray-400 mb-2">Hoàn thành</p>
      <p class="font-bold text-[#333] text-lg" id="statusCompletedValue"><?= $stats['order_status_distribution']['Completed'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
      <p class="text-xs text-gray-400 mb-2">Đã hủy</p>
      <p class="font-bold text-[#333] text-lg" id="statusCancelledValue"><?= $stats['order_status_distribution']['Cancelled'] ?? 0 ?></p>
    </div>
  </div>
  <!-- Month comparison -->
  <div class="bg-white rounded-xl shadow-sm p-5">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div>
        <p class="text-sm text-gray-400 mb-2">Doanh thu tháng này</p>
        <div class="flex items-baseline gap-3">
          <p class="text-3xl font-bold text-[#333]" id="thisMonthCompare"><?= number_format($stats['revenue_this_month'], 0, ',', '.') ?> ₫</p>
          <span class="text-xs px-2 py-1 rounded-full <?= $stats['revenue_change_percent'] >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
            <i data-lucide="<?= $stats['revenue_change_percent'] >= 0 ? 'arrow-up-right' : 'arrow-down-right' ?>" class="inline w-3 h-3"></i>
            <span id="changePercent"><?= abs($stats['revenue_change_percent']) ?>%</span>
          </span>
        </div>
        <p class="text-xs text-gray-400 mt-2">vs tháng trước: <span id="lastMonthCompare" class="font-semibold text-[#333]"><?= number_format($stats['revenue_last_month'], 0, ',', '.') ?> ₫</span></p>
      </div>

      <div>
        <p class="text-sm text-gray-400 mb-3">Doanh thu 7 ngày gần nhất</p>
        <canvas id="dailyRevenueChart" width="400" height="150"></canvas>
      </div>
    </div>
  </div>

  <!-- Bottom: recent tables -->
  <div class="grid lg:grid-cols-2 gap-4">

    <!-- Sách bán chạy -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-[#333] text-sm">Sản phẩm bán gần nhất</h3>
      </div>
      <div class="divide-y divide-gray-50" id="topBooksList">
        <?php if (!empty($stats['top_books'])): ?>
          <?php foreach ($stats['top_books'] as $index => $book): ?>
            <div class="flex items-center gap-3 px-5 py-4 top-book-row">
              <span class="text-xs font-bold w-5 <?= $index === 0 ? 'text-[#FFC107]' : 'text-gray-400' ?>">
                #<?= $index + 1 ?>
              </span>
              <div class="w-10 h-12 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="book" class="w-5 h-5 text-gray-400"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-[#333] truncate"><?= htmlspecialchars($book['title']) ?></p>
                <p class="text-xs text-gray-400">
                  <?= htmlspecialchars($book['author']) ?> · Đơn: #<?= htmlspecialchars($book['order_code']) ?>
                </p>
                <p class="text-xs text-gray-400">Khách: <?= htmlspecialchars($book['customer']) ?></p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-sm font-semibold text-[#4CAF50]">
                  <?= number_format($book['price'], 0, ',', '.') ?> ₫
                </p>
                <p class="text-xs text-gray-400">Bán: <?= date('d/m/Y', strtotime($book['created_at'])) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="px-5 py-4 text-sm text-gray-500">Chưa có sản phẩm nào được bán gần đây.</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Sách bán chạy nhất -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-[#333] text-sm">Sách bán chạy nhất</h3>
      </div>
      <div class="divide-y divide-gray-50" id="bestSellersList">
        <?php if (!empty($stats['best_sellers'])): ?>
          <?php foreach ($stats['best_sellers'] as $index => $book): ?>
            <div class="flex items-center gap-3 px-5 py-4 best-seller-row">
              <span class="text-xs font-bold w-5 <?= $index === 0 ? 'text-[#FFC107]' : 'text-gray-400' ?>">
                #<?= $index + 1 ?>
              </span>
              <div class="w-10 h-12 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="book" class="w-5 h-5 text-gray-400"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-[#333] truncate"><?= htmlspecialchars($book['title']) ?></p>
                <p class="text-xs text-gray-400"><?= htmlspecialchars($book['author']) ?></p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-sm font-semibold text-[#4CAF50]"><?= number_format($book['sold_quantity'], 0, ',', '.') ?> bán</p>
                <p class="text-xs text-gray-400">Doanh thu <?= number_format($book['revenue'], 0, ',', '.') ?> ₫</p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="px-5 py-4 text-sm text-gray-500">Chưa có sản phẩm bán chạy.</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-[#333] text-sm">Đơn hàng gần đây</h3>
      </div>
      <div class="divide-y divide-gray-50" id="recentOrdersList">
        <?php
        $statusClass = [
          'Pending' => 'bg-yellow-100 text-yellow-700',
          'Confirmed' => 'bg-blue-100 text-blue-700',
          'Shipping' => 'bg-blue-100 text-blue-700',
          'Completed' => 'bg-green-100 text-green-700',
          'Cancelled' => 'bg-red-100 text-red-500',
        ];
        if (!empty($stats['recent_orders'])):
          foreach ($stats['recent_orders'] as $order): ?>
            <div class="flex items-center gap-3 px-5 py-3 recent-order-row">
              <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="shopping-bag" class="w-[14px] h-[14px] text-gray-500"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-[#333]">#<?= htmlspecialchars($order['order_code']) ?></p>
                <p class="text-xs text-gray-400"><?= htmlspecialchars($order['customer']) ?> · <?= date('d/m/Y', strtotime($order['created_at'])) ?></p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-sm font-semibold"><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</p>
                <span class="text-xs px-2 py-0.5 rounded-full <?= $statusClass[$order['status_name']] ?? 'bg-gray-100 text-gray-500' ?>">
                  <?= htmlspecialchars($order['status_name'] ?? 'Không xác định') ?>
                </span>
              </div>
            </div>
          <?php endforeach;
        else: ?>
          <div class="px-5 py-4 text-sm text-gray-500">Chưa có đơn hàng gần đây.</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Top khách hàng chi tiêu -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-[#333] text-sm">Top khách hàng chi tiêu</h3>
      </div>
      <div class="divide-y divide-gray-50" id="topCustomersList">
        <?php if (!empty($stats['top_customers'])): ?>
          <?php foreach ($stats['top_customers'] as $index => $customer): ?>
            <div class="flex items-center gap-3 px-5 py-4">
              <span class="text-xs font-bold w-5 <?= $index === 0 ? 'text-[#FFC107]' : 'text-gray-400' ?>">#<?= $index + 1 ?></span>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-[#333] truncate"><?= htmlspecialchars($customer['customer']) ?></p>
                <p class="text-xs text-gray-400">Đơn: <?= htmlspecialchars($customer['order_count']) ?></p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-sm font-semibold text-[#4CAF50]"><?= number_format($customer['total_spent'], 0, ',', '.') ?> ₫</p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="px-5 py-4 text-sm text-gray-500">Chưa có khách hàng đủ điều kiện.</div>
        <?php endif; ?>
      </div>
    </div>



  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('revenueChart').getContext('2d');
  let revenueChartInstance = null;
  const monthlyData = <?php echo json_encode($stats['monthly_revenue']); ?>;
  const labels = monthlyData.map(item => `${item.month}/${item.year}`);
  const revenues = monthlyData.map(item => parseFloat(item.revenue));
  const hasRevenue = revenues.some(value => value > 0);

  if (!hasRevenue) {
    document.getElementById('revenueChartContainer').classList.add('hidden');
    document.getElementById('revenueChartEmpty').classList.remove('hidden');
  } else {
    revenueChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: revenues,
          borderColor: '#4CAF50',
          backgroundColor: 'rgba(76, 175, 80, 0.15)',
          tension: 0.4,
          fill: true,
          pointRadius: 3,
          pointBackgroundColor: '#4CAF50',
          pointBorderColor: '#ffffff',
          pointHoverRadius: 5
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'top'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
              }
            }
          }
        }
      }
    });
  }

  // Daily revenue chart
  let dailyChartInstance = null;
  const dailyData = <?php echo json_encode($stats['daily_revenue']); ?>;
  if (dailyData.length > 0) {
    const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    dailyChartInstance = new Chart(dailyCtx, {
      type: 'bar',
      data: {
        labels: dailyData.map(item => item.day),
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: dailyData.map(item => parseFloat(item.revenue)),
          backgroundColor: '#4CAF50',
          borderColor: '#4CAF50',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return new Intl.NumberFormat('vi-VN').format(value);
              }
            }
          }
        }
      }
    });
  }

  async function refreshDashboardWidgets() {
    try {
      const response = await fetch('<?= BASE_URL ?>?act=admin-dashboard-data');
      if (!response.ok) {
        return;
      }

      const data = await response.json();
      const topBooksList = document.getElementById('topBooksList');
      const bestSellersList = document.getElementById('bestSellersList');
      const recentOrdersList = document.getElementById('recentOrdersList');
      const topCustomersList = document.getElementById('topCustomersList');
      const lowStockBooksList = document.getElementById('lowStockBooksList');

      if (Array.isArray(data.top_books) && data.top_books.length > 0) {
        topBooksList.innerHTML = data.top_books.map((book, index) => `
          <div class="flex items-center gap-3 px-5 py-4 top-book-row">
            <span class="text-xs font-bold w-5 ${index === 0 ? 'text-[#FFC107]' : 'text-gray-400'}">
              #${index + 1}
            </span>
            <div class="w-10 h-12 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
              <i data-lucide="book" class="w-5 h-5 text-gray-400"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-[#333] truncate">${book.title}</p>
              <p class="text-xs text-gray-400">${book.author} · Đơn: #${book.order_code}</p>
              <p class="text-xs text-gray-400">Khách: ${book.customer}</p>
            </div>
            <div class="text-right shrink-0">
              <p class="text-sm font-semibold text-[#4CAF50]">${new Intl.NumberFormat('vi-VN').format(book.price)} ₫</p>
              <p class="text-xs text-gray-400">Bán: ${new Date(book.created_at).toLocaleDateString('vi-VN')}</p>
            </div>
          </div>
        `).join('');
      } else {
        topBooksList.innerHTML = '<div class="px-5 py-4 text-sm text-gray-500">Chưa có sản phẩm nào được bán gần đây.</div>';
      }

      if (Array.isArray(data.best_sellers) && data.best_sellers.length > 0) {
        bestSellersList.innerHTML = data.best_sellers.map((book, index) => `
          <div class="flex items-center gap-3 px-5 py-4 best-seller-row">
            <span class="text-xs font-bold w-5 ${index === 0 ? 'text-[#FFC107]' : 'text-gray-400'}">
              #${index + 1}
            </span>
            <div class="w-10 h-12 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
              <i data-lucide="book" class="w-5 h-5 text-gray-400"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-[#333] truncate">${book.title}</p>
              <p class="text-xs text-gray-400">${book.author}</p>
            </div>
            <div class="text-right shrink-0">
              <p class="text-sm font-semibold text-[#4CAF50]">${new Intl.NumberFormat('vi-VN').format(book.sold_quantity)} bán</p>
              <p class="text-xs text-gray-400">Doanh thu ${new Intl.NumberFormat('vi-VN').format(book.revenue)} ₫</p>
            </div>
          </div>
        `).join('');
      } else {
        bestSellersList.innerHTML = '<div class="px-5 py-4 text-sm text-gray-500">Chưa có sản phẩm bán chạy.</div>';
      }

      const statusClass = {
        Pending: 'bg-yellow-100 text-yellow-700',
        Confirmed: 'bg-blue-100 text-blue-700',
        Shipping: 'bg-blue-100 text-blue-700',
        Completed: 'bg-green-100 text-green-700',
        Cancelled: 'bg-red-100 text-red-500',
      };

      if (Array.isArray(data.recent_orders) && data.recent_orders.length > 0) {
        recentOrdersList.innerHTML = data.recent_orders.map(order => `
          <div class="flex items-center gap-3 px-5 py-3 recent-order-row">
            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
              <i data-lucide="shopping-bag" class="w-[14px] h-[14px] text-gray-500"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-[#333]">#${order.order_code}</p>
              <p class="text-xs text-gray-400">${order.customer} · ${new Date(order.created_at).toLocaleDateString('vi-VN')}</p>
            </div>
            <div class="text-right shrink-0">
              <p class="text-sm font-semibold">${new Intl.NumberFormat('vi-VN').format(order.total_amount)} ₫</p>
              <span class="text-xs px-2 py-0.5 rounded-full ${statusClass[order.status_name] ?? 'bg-gray-100 text-gray-500'}">
                ${order.status_name || 'Không xác định'}
              </span>
            </div>
          </div>
        `).join('');
      } else {
        recentOrdersList.innerHTML = '<div class="px-5 py-4 text-sm text-gray-500">Chưa có đơn hàng gần đây.</div>';
      }

      const updateStatText = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
      };

      updateStatText('totalRevenueValue', new Intl.NumberFormat('vi-VN').format(data.total_revenue) + ' ₫');
      updateStatText('totalOrdersValue', data.total_orders);
      updateStatText('totalBooksValue', data.total_books);
      updateStatText('totalCustomersValue', data.total_customers);
      updateStatText('revenueMonthValue', new Intl.NumberFormat('vi-VN').format(data.revenue_this_month) + ' ₫');
      updateStatText('pendingOrdersValue', data.pending_orders);
      updateStatText('newCustomersValue', data.new_customers);
      updateStatText('lowStockBooksValue', data.low_stock_books);
      updateStatText('statusPendingValue', data.order_status_distribution?.Pending ?? 0);
      updateStatText('statusConfirmedValue', data.order_status_distribution?.Confirmed ?? 0);
      updateStatText('statusShippingValue', data.order_status_distribution?.Shipping ?? 0);
      updateStatText('statusCompletedValue', data.order_status_distribution?.Completed ?? 0);
      updateStatText('statusCancelledValue', data.order_status_distribution?.Cancelled ?? 0);
      updateStatText('todayOrdersValue', data.today_orders);
      updateStatText('revenueTodayValue', new Intl.NumberFormat('vi-VN').format(data.revenue_today) + ' ₫');
      updateStatText('averageOrderValue', new Intl.NumberFormat('vi-VN').format(data.average_order_value) + ' ₫');

      if (Array.isArray(data.top_customers) && topCustomersList) {
        if (data.top_customers.length > 0) {
          topCustomersList.innerHTML = data.top_customers.map((customer, index) => `
            <div class="flex items-center gap-3 px-5 py-4">
              <span class="text-xs font-bold w-5 ${index === 0 ? 'text-[#FFC107]' : 'text-gray-400'}">#${index + 1}</span>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-[#333] truncate">${customer.customer}</p>
                <p class="text-xs text-gray-400">Đơn: ${customer.order_count}</p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-sm font-semibold text-[#4CAF50]">${new Intl.NumberFormat('vi-VN').format(customer.total_spent)} ₫</p>
              </div>
            </div>
          `).join('');
        } else {
          topCustomersList.innerHTML = '<div class="px-5 py-4 text-sm text-gray-500">Chưa có khách hàng đủ điều kiện.</div>';
        }
      }



      if (Array.isArray(data.monthly_revenue) && revenueChartInstance) {
        const updatedLabels = data.monthly_revenue.map(item => `${item.month}/${item.year}`);
        const updatedRevenues = data.monthly_revenue.map(item => parseFloat(item.revenue));
        if (updatedRevenues.some(value => value > 0)) {
          document.getElementById('revenueChartContainer').classList.remove('hidden');
          document.getElementById('revenueChartEmpty').classList.add('hidden');
          revenueChartInstance.data.labels = updatedLabels;
          revenueChartInstance.data.datasets[0].data = updatedRevenues;
          revenueChartInstance.update();
        } else {
          document.getElementById('revenueChartContainer').classList.add('hidden');
          document.getElementById('revenueChartEmpty').classList.remove('hidden');
        }
      }

      if (window.lucide) {
        window.lucide.replace();
      }
    } catch (error) {
      console.error('Không thể làm mới dashboard:', error);
    }
  }

  refreshDashboardWidgets();
  setInterval(refreshDashboardWidgets, 10000);
});
</script>

<?php require_once './views/components/footer.php'; ?>