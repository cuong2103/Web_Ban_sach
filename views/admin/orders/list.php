<?php 
include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
              
              <div class="flex justify-between items-center mb-6">
                  <div>
                      <h1 class="text-2xl font-bold text-gray-900">Quản lý Đơn hàng</h1>
                      <p class="text-sm text-gray-500 mt-1">Danh sách tất cả đơn hàng từ khách hàng</p>
                  </div>

                  <?php
                    // Lấy số đơn đang chờ để hiện badge (dùng trước khi $dailyStats được khởi tạo bên dưới)
                    $pendingCount = (int)(($dailyStats['pending_orders'] ?? 0));
                  ?>
                  <form method="POST" action="<?= BASE_URL ?>?act=admin-orders-confirm-all"
                        onsubmit="return confirm('Bạn có chắc muốn xác nhận tất cả <?= $pendingCount ?> đơn hàng đang chờ không?')">
                      <button type="submit"
                              <?= $pendingCount === 0 ? 'disabled' : '' ?>
                              class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-medium text-sm transition-all
                                     <?= $pendingCount > 0
                                           ? 'bg-[#4CAF50] text-white hover:bg-green-600 shadow-sm hover:shadow-md'
                                           : 'bg-gray-100 text-gray-400 cursor-not-allowed' ?>">
                          <i data-lucide="check-check" class="w-4 h-4"></i>
                          Xác nhận tất cả
                          <?php if ($pendingCount > 0): ?>
                              <span class="bg-white/30 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                  <?= $pendingCount ?>
                              </span>
                          <?php endif; ?>
                      </button>
                  </form>
              </div>

              <!-- Thống kê đơn hàng hôm nay -->
              <?php
                $todayOrders     = (int)($dailyStats['today_orders'] ?? 0);
                $todayRevenue    = (float)($dailyStats['today_revenue'] ?? 0);
                $pendingOrders   = (int)($dailyStats['pending_orders'] ?? 0);
                $cancelledOrders = (int)($dailyStats['cancelled_orders'] ?? 0);
              ?>
              <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

                <!-- Đơn hàng hôm nay -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-blue-500"></i>
                  </div>
                  <div class="min-w-0">
                    <p class="text-xs text-gray-500 mb-0.5">Đơn hàng hôm nay</p>
                    <p class="text-2xl font-bold text-gray-900"><?= number_format($todayOrders) ?></p>
                  </div>
                </div>

                <!-- Doanh thu hôm nay -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="trending-up" class="w-6 h-6 text-green-500"></i>
                  </div>
                  <div class="min-w-0">
                    <p class="text-xs text-gray-500 mb-0.5">Doanh thu hôm nay</p>
                    <p class="text-xl font-bold text-gray-900"><?= number_format($todayRevenue, 0, ',', '.') ?>đ</p>
                  </div>
                </div>

                <!-- Chờ xác nhận -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-500"></i>
                  </div>
                  <div class="min-w-0">
                    <p class="text-xs text-gray-500 mb-0.5">Chờ xác nhận</p>
                    <p class="text-2xl font-bold text-gray-900"><?= number_format($pendingOrders) ?></p>
                  </div>
                </div>

                <!-- Đã hủy hôm nay -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="x-circle" class="w-6 h-6 text-red-500"></i>
                  </div>
                  <div class="min-w-0">
                    <p class="text-xs text-gray-500 mb-0.5">Đã hủy hôm nay</p>
                    <p class="text-2xl font-bold text-gray-900"><?= number_format($cancelledOrders) ?></p>
                  </div>
                </div>

              </div>

              <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                  
                  <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                      <form action="" method="GET" class="flex flex-wrap gap-4 items-center justify-between w-full">
                          <input type="hidden" name="act" value="admin-orders">
                          
                          <div class="flex flex-1 min-w-[300px] gap-4">
                              <div class="relative flex-1 min-w-[200px]">
                                  <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                  <input type="text" 
                                         name="search" 
                                         value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                                         placeholder="Tìm theo mã đơn hoặc tên khách..." 
                                         class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                              </div>

                              <select name="status_id" class="px-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]">
                                  <option value="">-- Tất cả trạng thái --</option>
                                  <?php foreach ($statuses as $st): ?>
                                      <option value="<?= $st['status_id'] ?>" <?= (isset($_GET['status_id']) && $_GET['status_id'] == $st['status_id']) ? 'selected' : '' ?>>
                                          <?= htmlspecialchars($st['status_name']) ?>
                                      </option>
                                  <?php endforeach; ?>
                              </select>
                              
                              <button type="submit" class="px-6 py-2 bg-[#1B2537] text-white rounded-xl hover:bg-gray-800 transition-colors font-medium">
                                  Lọc
                              </button>
                          </div>
                      </form>
                  </div>

                  <div class="overflow-x-auto">
                      <table class="w-full text-left border-collapse">
                          <thead>
                              <tr class="bg-gray-50/50 border-b border-gray-100">
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã đơn</th>
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao tác</th>
                              </tr>
                          </thead>
                          <tbody class="divide-y divide-gray-100">
                              <?php if (empty($orders)): ?>
                                  <tr>
                                      <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                          Không tìm thấy đơn hàng nào
                                      </td>
                                  </tr>
                              <?php else: ?>
                                  <?php foreach ($orders as $order): ?>
                                  <tr class="hover:bg-gray-50 transition-colors">
                                      <td class="px-6 py-4">
                                          <span class="font-medium text-gray-900"><?= htmlspecialchars($order['order_code']) ?></span>
                                      </td>
                                      <td class="px-6 py-4">
                                          <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($order['customer_name']) ?></div>
                                      </td>
                                      <td class="px-6 py-4 text-sm text-gray-500">
                                          <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                      </td>
                                      <td class="px-6 py-4">
                                          <div class="font-medium text-[#4CAF50]"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</div>
                                      </td>
                                      <td class="px-6 py-4">
                                          <?php
                                              $statusColors = [
                                                  1 => 'bg-yellow-100 text-yellow-800', // Pending
                                                  2 => 'bg-blue-100 text-blue-800',     // Confirmed
                                                  3 => 'bg-indigo-100 text-indigo-800',  // Shipping
                                                  4 => 'bg-green-100 text-green-800',   // Completed
                                                  5 => 'bg-red-100 text-red-800'        // Cancelled
                                              ];
                                              $colorClass = $statusColors[$order['status_id']] ?? 'bg-gray-100 text-gray-800';
                                          ?>
                                          <span class="px-3 py-1 text-xs font-medium rounded-full <?= $colorClass ?>">
                                              <?= htmlspecialchars($order['status_name']) ?>
                                          </span>
                                      </td>
                                      <td class="px-6 py-4 text-right">
                                          <div class="flex items-center justify-end gap-2">
                                              <a href="<?= BASE_URL ?>?act=admin-order-detail&id=<?= $order['order_id'] ?>" 
                                                 class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-medium flex items-center gap-1.5 whitespace-nowrap"
                                                 title="Xem chi tiết">
                                                  <i data-lucide="eye" class="w-3.5 h-3.5"></i> Chi tiết
                                              </a>
                                          </div>
                                      </td>
                                  </tr>
                                  <?php endforeach; ?>
                              <?php endif; ?>
                          </tbody>
                      </table>
                  </div>

                  <?php if ($totalPages > 1): ?>
                  <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
                      <div class="text-sm text-gray-500">
                          Hiển thị trang <span class="font-medium text-gray-900"><?= $page ?></span> / <span class="font-medium text-gray-900"><?= $totalPages ?></span>
                      </div>
                      
                      <div class="flex gap-1">
                          <?php
                          $baseUrl = BASE_URL . "?act=admin-orders";
                          if (!empty($search)) $baseUrl .= "&search=" . urlencode($search);
                          if (!empty($statusId)) $baseUrl .= "&status_id=" . urlencode($statusId);
                          ?>
                          
                          <?php if ($page > 1): ?>
                              <a href="<?= $baseUrl ?>&page=<?= $page - 1 ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 flex items-center">
                                  <i data-lucide="chevron-left" class="w-4 h-4"></i>
                              </a>
                          <?php endif; ?>

                          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                              <a href="<?= $baseUrl ?>&page=<?= $i ?>" 
                                 class="px-3 py-1.5 border rounded-lg <?= $i === $page ? 'bg-[#4CAF50] border-[#4CAF50] text-white' : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50' ?>">
                                  <?= $i ?>
                              </a>
                          <?php endfor; ?>

                          <?php if ($page < $totalPages): ?>
                              <a href="<?= $baseUrl ?>&page=<?= $page + 1 ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-gray-600 hover:bg-gray-50 flex items-center">
                                  <i data-lucide="chevron-right" class="w-4 h-4"></i>
                              </a>
                          <?php endif; ?>
                      </div>
                  </div>
                  <?php endif; ?>

              </div>
        </div>
    </main>
<?php include_once './views/components/footer.php'; ?>
