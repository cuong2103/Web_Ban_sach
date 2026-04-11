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

  <!-- Revenue Chart -->
  <div class="bg-white rounded-xl shadow-sm p-5">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-semibold text-[#333] text-sm">Biểu đồ Doanh thu</h3>
      <div class="flex gap-2">
          <select id="chartMonthFilter" class="text-xs border border-gray-200 rounded-md px-2 py-1 outline-none hover:border-gray-300">
              <option value="all">Tất cả tháng</option>
              <?php for($i=1; $i<=12; $i++): ?>
                <option value="<?= $i ?>">Tháng <?= $i ?></option>
              <?php endfor; ?>
          </select>
          <select id="chartYearFilter" class="text-xs border border-gray-200 rounded-md px-2 py-1 outline-none hover:border-gray-300">
              <?php $currentYear = date('Y'); for($i=$currentYear-2; $i<=$currentYear; $i++): ?>
                <option value="<?= $i ?>" <?= $i == $currentYear ? 'selected' : '' ?>>Năm <?= $i ?></option>
              <?php endfor; ?>
          </select>
      </div>
    </div>
    <div id="revenueChartContainer">
      <canvas id="revenueChart" width="400" height="120"></canvas>
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

    <!-- Sách bán chạy nhất -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-[#333] text-sm">Sách bán chạy tháng này</h3>
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

    <!-- Top khách hàng chi tiêu -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-[#333] text-sm">Top khách hàng chi tiêu</h3>
        <div class="flex gap-2">
            <select id="tcMonthFilter" class="text-xs border border-gray-200 rounded-md px-2 py-1 outline-none hover:border-gray-300">
                <option value="all">Tất cả tháng</option>
                <?php for($i=1; $i<=12; $i++): ?>
                  <option value="<?= $i ?>" <?= date('m') == $i ? 'selected' : '' ?>>Tháng <?= $i ?></option>
                <?php endfor; ?>
            </select>
            <select id="tcYearFilter" class="text-xs border border-gray-200 rounded-md px-2 py-1 outline-none hover:border-gray-300">
                <?php for($i=$currentYear-2; $i<=$currentYear; $i++): ?>
                  <option value="<?= $i ?>" <?= date('Y') == $i ? 'selected' : '' ?>>Năm <?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
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
          <div class="px-5 py-4 text-sm text-gray-500">Không có khách hàng chi tiêu trong thời gian này.</div>
        <?php endif; ?>
      </div>
    </div>

  </div>




</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('revenueChart').getContext('2d');
  let revenueChartInstance = null;
  const initialChartData = <?php echo json_encode($stats['revenue_chart_data'] ?? []); ?>;
  const labels = initialChartData.map(item => item.label);
  const revenues = initialChartData.map(item => parseFloat(item.revenue));

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
      const tcMonth = document.getElementById('tcMonthFilter')?.value || '<?= date('m') ?>';
      const tcYear = document.getElementById('tcYearFilter')?.value || '<?= date('Y') ?>';
      const chartMonth = document.getElementById('chartMonthFilter')?.value || 'all';
      const chartYear = document.getElementById('chartYearFilter')?.value || '<?= date('Y') ?>';
      
      const response = await fetch(`<?= BASE_URL ?>?act=admin-dashboard-data&tc_month=${tcMonth}&tc_year=${tcYear}&chart_month=${chartMonth}&chart_year=${chartYear}`);
      if (!response.ok) {
        return;
      }

      const data = await response.json();
      const topBooksList = document.getElementById('topBooksList');
      const bestSellersList = document.getElementById('bestSellersList');
      const recentOrdersList = document.getElementById('recentOrdersList');
      const topCustomersList = document.getElementById('topCustomersList');
      const lowStockBooksList = document.getElementById('lowStockBooksList');

      if (topBooksList) {
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
      }

      if (bestSellersList) {
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
      }

      const statusClass = {
        'Chờ xác nhận': 'bg-yellow-100 text-yellow-700',
        'Đã xác nhận': 'bg-blue-100 text-blue-700',
        'Đang giao hàng': 'bg-blue-100 text-blue-700',
        'Hoàn thành': 'bg-green-100 text-green-700',
        'Đã hủy': 'bg-red-100 text-red-500',
      };

      if (recentOrdersList) {
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
      updateStatText('statusPendingValue', data.order_status_distribution?.['Chờ xác nhận'] ?? 0);
      updateStatText('statusConfirmedValue', data.order_status_distribution?.['Đã xác nhận'] ?? 0);
      updateStatText('statusShippingValue', data.order_status_distribution?.['Đang giao hàng'] ?? 0);
      updateStatText('statusCompletedValue', data.order_status_distribution?.['Hoàn thành'] ?? 0);
      updateStatText('statusCancelledValue', data.order_status_distribution?.['Đã hủy'] ?? 0);
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
          topCustomersList.innerHTML = '<div class="px-5 py-4 text-sm text-gray-500">Không có khách hàng chi tiêu trong thời gian này.</div>';
        }
      }



      if (Array.isArray(data.revenue_chart_data) && revenueChartInstance) {
        const updatedLabels = data.revenue_chart_data.map(item => item.label);
        const updatedRevenues = data.revenue_chart_data.map(item => parseFloat(item.revenue));
        
        revenueChartInstance.data.labels = updatedLabels;
        revenueChartInstance.data.datasets[0].data = updatedRevenues;
        revenueChartInstance.update();
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

  // Gắn sự kiện cho các filters
  ['tcMonthFilter', 'tcYearFilter', 'chartMonthFilter', 'chartYearFilter'].forEach(id => {
    const el = document.getElementById(id);
    if(el) {
      el.addEventListener('change', refreshDashboardWidgets);
    }
  });
});
</script>

<?php require_once './views/components/footer.php'; ?>