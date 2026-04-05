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
      <p class="font-bold text-[#333]"><?= number_format($stats['total_revenue'], 0, ',', '.') ?> ₫</p>
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
      <p class="font-bold text-[#333]"><?= $stats['total_orders'] ?></p>
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
      <p class="font-bold text-[#333]"><?= $stats['total_books'] ?></p>
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
      <p class="font-bold text-[#333]"><?= $stats['total_customers'] ?></p>
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

  <!-- Bottom: recent tables -->
  <div class="grid lg:grid-cols-2 gap-4">

    <!-- Sách bán chạy -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-[#333] text-sm">Sách bán chạy</h3>
      </div>
      <div class="divide-y divide-gray-50">
        <?php if (!empty($stats['top_books'])): ?>
          <?php foreach ($stats['top_books'] as $index => $book): ?>
            <div class="flex items-center gap-3 px-5 py-3">
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
                <p class="text-sm font-semibold text-[#4CAF50]">
                  <?= number_format($book['price'], 0, ',', '.') ?> ₫
                </p>
                <p class="text-xs text-gray-400"><?= $book['sold'] ?> đã bán</p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="px-5 py-4 text-sm text-gray-500">Chưa có dữ liệu sách bán chạy.</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-[#333] text-sm">Đơn hàng gần đây</h3>
      </div>
      <div class="divide-y divide-gray-50">
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
            <div class="flex items-center gap-3 px-5 py-3">
              <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="shopping-bag" class="w-[14px] h-[14px] text-gray-500"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-[#333]">#<?= htmlspecialchars($order['order_code']) ?></p>
                <p class="text-xs text-gray-400"><?= htmlspecialchars($order['customer']) ?> · <?= date('d/m/Y', strtotime($order['created_at'])) ?></p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-sm font-semibold"><?= number_format($order['total_amount'] - $order['discount_amount'], 0, ',', '.') ?> ₫</p>
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

  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('revenueChart').getContext('2d');
  const monthlyData = <?php echo json_encode($stats['monthly_revenue']); ?>;
  const labels = monthlyData.map(item => `${item.month}/${item.year}`);
  const revenues = monthlyData.map(item => parseFloat(item.revenue));
  const hasRevenue = revenues.some(value => value > 0);

  if (!hasRevenue) {
    document.getElementById('revenueChartContainer').classList.add('hidden');
    document.getElementById('revenueChartEmpty').classList.remove('hidden');
    return;
  }

  new Chart(ctx, {
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
});
</script>

<?php require_once './views/components/footer.php'; ?>