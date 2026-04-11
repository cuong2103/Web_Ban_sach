<?php
class DashboardController
{
  public function Dashboard()
  {
    // TODO: Bật lại khi làm module auth
    // requireAdmin();
    $currentUser = $_SESSION['currentUser'] ?? [];
    $fullname = $currentUser['fullname'] ?? 'Admin';

    $tcMonth = $_GET['tc_month'] ?? date('m');
    $tcYear = $_GET['tc_year'] ?? date('Y');
    $chartMonth = $_GET['chart_month'] ?? 'all';
    $chartYear = $_GET['chart_year'] ?? date('Y');

    // Lấy thống kê
    $stats = $this->getDashboardStats($tcMonth, $tcYear, $chartMonth, $chartYear);

    require_once './views/admin/dashboard.php';
  }

  public function dashboardData()
  {
    $tcMonth = $_GET['tc_month'] ?? date('m');
    $tcYear = $_GET['tc_year'] ?? date('Y');
    $chartMonth = $_GET['chart_month'] ?? 'all';
    $chartYear = $_GET['chart_year'] ?? date('Y');

    $stats = $this->getDashboardStats($tcMonth, $tcYear, $chartMonth, $chartYear);
    header('Content-Type: application/json');
    echo json_encode([
      'top_books' => $stats['top_books'] ?? [],
      'best_sellers' => $stats['best_sellers'] ?? [],
      'recent_orders' => $stats['recent_orders'] ?? [],
      'top_customers' => $stats['top_customers'] ?? [],
      'revenue_chart_data' => $stats['revenue_chart_data'] ?? [],
      'low_stock_books_list' => $stats['low_stock_books_list'] ?? [],
      'revenue_this_month' => $stats['revenue_this_month'] ?? 0,
      'revenue_last_month' => $stats['revenue_last_month'] ?? 0,
      'revenue_change_percent' => $stats['revenue_change_percent'] ?? 0,
      'revenue_today' => $stats['revenue_today'] ?? 0,
      'today_orders' => $stats['today_orders'] ?? 0,
      'average_order_value' => $stats['average_order_value'] ?? 0,
      'pending_orders' => $stats['pending_orders'] ?? 0,
      'new_customers' => $stats['new_customers'] ?? 0,
      'low_stock_books' => $stats['low_stock_books'] ?? 0,
      'total_revenue' => $stats['total_revenue'] ?? 0,
      'total_orders' => $stats['total_orders'] ?? 0,
      'total_books' => $stats['total_books'] ?? 0,
      'total_customers' => $stats['total_customers'] ?? 0,
      'order_status_distribution' => $stats['order_status_distribution'] ?? [],
      'daily_revenue' => $stats['daily_revenue'] ?? [],
      'weekly_revenue' => $stats['weekly_revenue'] ?? [],
      'monthly_revenue' => $stats['monthly_revenue'] ?? [],
    ]);
    exit;
  }

  private function getDashboardStats($tcMonth = null, $tcYear = null, $chartMonth = null, $chartYear = null)
  {
    $conn = connectDB();

    // Tổng doanh thu (dùng total_amount đã bao gồm giảm giá)
    $revenueQuery = "SELECT COALESCE(SUM(total_amount), 0) as total_revenue FROM orders WHERE status_id != 5";
    $revenueStmt = $conn->prepare($revenueQuery);
    $revenueStmt->execute();
    $revenue = $revenueStmt->fetch()['total_revenue'] ?? 0;

    // Tổng đơn hàng
    $ordersQuery = "SELECT COUNT(*) as total_orders FROM orders";
    $ordersStmt = $conn->prepare($ordersQuery);
    $ordersStmt->execute();
    $totalOrders = $ordersStmt->fetch()['total_orders'] ?? 0;

    // Tổng sách
    $booksQuery = "SELECT COUNT(*) as total_books FROM books WHERE status = 1";
    $booksStmt = $conn->prepare($booksQuery);
    $booksStmt->execute();
    $totalBooks = $booksStmt->fetch()['total_books'] ?? 0;

    // Tổng khách hàng
    $customersQuery = "SELECT COUNT(*) as total_customers FROM users WHERE role_id = 2";
    $customersStmt = $conn->prepare($customersQuery);
    $customersStmt->execute();
    $totalCustomers = $customersStmt->fetch()['total_customers'] ?? 0;

    // Doanh thu tháng hiện tại
    $revenueMonthQuery = "SELECT COALESCE(SUM(total_amount), 0) as revenue_this_month FROM orders WHERE status_id != 5 AND YEAR(created_at) = YEAR(CURRENT_DATE()) AND MONTH(created_at) = MONTH(CURRENT_DATE())";
    $revenueMonthStmt = $conn->prepare($revenueMonthQuery);
    $revenueMonthStmt->execute();
    $revenueThisMonth = $revenueMonthStmt->fetch()['revenue_this_month'] ?? 0;

    // Đơn hàng theo trạng thái
    $statusQuery = "SELECT os.status_name, COUNT(*) as order_count FROM orders o LEFT JOIN order_status os ON os.status_id = o.status_id GROUP BY o.status_id";
    $statusStmt = $conn->prepare($statusQuery);
    $statusStmt->execute();
    $orderStatusRows = $statusStmt->fetchAll();

    $orderStatusDistribution = [
      'Chờ xác nhận' => 0,
      'Đã xác nhận' => 0,
      'Đang giao hàng' => 0,
      'Hoàn thành' => 0,
      'Đã hủy' => 0,
    ];
    foreach ($orderStatusRows as $row) {
      $key = $row['status_name'] ?? 'Chờ xác nhận';
      $orderStatusDistribution[$key] = (int)$row['order_count'];
    }

    $pendingOrders = $orderStatusDistribution['Chờ xác nhận'] ?? 0;

    // Khách hàng mới trong tháng
    $newCustomersQuery = "SELECT COUNT(*) as new_customers FROM users WHERE role_id = 2 AND YEAR(created_at) = YEAR(CURRENT_DATE()) AND MONTH(created_at) = MONTH(CURRENT_DATE())";
    $newCustomersStmt = $conn->prepare($newCustomersQuery);
    $newCustomersStmt->execute();
    $newCustomersThisMonth = $newCustomersStmt->fetch()['new_customers'] ?? 0;

    // Sách tồn kho thấp
    $lowStockQuery = "SELECT COUNT(*) as low_stock_books FROM books WHERE status = 1 AND stock <= 5";
    $lowStockStmt = $conn->prepare($lowStockQuery);
    $lowStockStmt->execute();
    $lowStockBooks = $lowStockStmt->fetch()['low_stock_books'] ?? 0;

    // Doanh thu hôm nay
    $revenueTodayQuery = "SELECT COALESCE(SUM(total_amount), 0) as revenue_today FROM orders WHERE status_id != 5 AND DATE(created_at) = CURRENT_DATE()";
    $revenueTodayStmt = $conn->prepare($revenueTodayQuery);
    $revenueTodayStmt->execute();
    $revenueToday = $revenueTodayStmt->fetch()['revenue_today'] ?? 0;

    // Đơn hôm nay
    $todayOrdersQuery = "SELECT COUNT(*) as today_orders FROM orders WHERE DATE(created_at) = CURRENT_DATE()";
    $todayOrdersStmt = $conn->prepare($todayOrdersQuery);
    $todayOrdersStmt->execute();
    $todayOrders = $todayOrdersStmt->fetch()['today_orders'] ?? 0;

    // Giá trị trung bình đơn hàng
    $averageOrderQuery = "SELECT COALESCE(AVG(total_amount), 0) as average_order_value FROM orders WHERE status_id != 5";
    $averageOrderStmt = $conn->prepare($averageOrderQuery);
    $averageOrderStmt->execute();
    $averageOrderValue = $averageOrderStmt->fetch()['average_order_value'] ?? 0;

    // Top khách hàng chi tiêu
    $topCustomersQuery = "SELECT u.user_id, u.full_name AS customer, COUNT(o.order_id) as order_count, COALESCE(SUM(o.total_amount), 0) as total_spent
      FROM users u
      JOIN orders o ON o.user_id = u.user_id
      WHERE u.role_id = 2 AND o.status_id != 5";

    if ($tcYear && $tcYear !== 'all') {
      $topCustomersQuery .= " AND YEAR(o.created_at) = " . (int)$tcYear;
    }
    if ($tcMonth && $tcMonth !== 'all') {
      $topCustomersQuery .= " AND MONTH(o.created_at) = " . (int)$tcMonth;
    }

    $topCustomersQuery .= " GROUP BY u.user_id, u.full_name
      ORDER BY total_spent DESC
      LIMIT 5";

    $topCustomersStmt = $conn->prepare($topCustomersQuery);
    $topCustomersStmt->execute();
    $topCustomers = $topCustomersStmt->fetchAll();

    // Sách tồn kho thấp chi tiết
    $lowStockBooksQuery = "SELECT book_id, title, author, stock FROM books WHERE status = 1 AND stock <= 5 ORDER BY stock ASC, title ASC LIMIT 5";
    $lowStockBooksStmt = $conn->prepare($lowStockBooksQuery);
    $lowStockBooksStmt->execute();
    $lowStockBooksList = $lowStockBooksStmt->fetchAll();

    // Doanh thu hôm nay (ký quỹ để tính sau)
    // $revenueToday đã có ở trên

    // Doanh thu theo ngày (7 ngày gần nhất)
    $dailyRevenueQuery = "SELECT 
        DATE(created_at) as day,
        COALESCE(SUM(total_amount), 0) as revenue
      FROM orders
      WHERE status_id != 5 AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 DAY)
      GROUP BY DATE(created_at)
      ORDER BY DATE(created_at) ASC";
    $dailyStmt = $conn->prepare($dailyRevenueQuery);
    $dailyStmt->execute();
    $dailyRevenueRows = $dailyStmt->fetchAll();

    $dailyRevenue = [];
    for ($i = 6; $i >= 0; $i--) {
      $date = new DateTime("-{$i} day");
      $key = $date->format('Y-m-d');
      $dailyRevenue[$key] = [
        'day' => $date->format('d/m'),
        'revenue' => 0
      ];
    }
    foreach ($dailyRevenueRows as $row) {
      if (isset($dailyRevenue[$row['day']])) {
        $dailyRevenue[$row['day']]['revenue'] = (float) $row['revenue'];
      }
    }

    // Doanh thu theo tuần (12 tuần gần nhất)
    $weeklyRevenueQuery = "SELECT 
        YEAR(created_at) as year,
        WEEK(created_at, 1) as week,
        COALESCE(SUM(total_amount), 0) as revenue
      FROM orders
      WHERE status_id != 5
      GROUP BY YEAR(created_at), WEEK(created_at, 1)
      ORDER BY YEAR(created_at), WEEK(created_at, 1) DESC
      LIMIT 12";
    $weeklyStmt = $conn->prepare($weeklyRevenueQuery);
    $weeklyStmt->execute();
    $weeklyRevenueRows = $weeklyStmt->fetchAll();

    $weeklyRevenue = [];
    foreach (array_reverse($weeklyRevenueRows) as $row) {
      $weeklyRevenue[] = [
        'label' => 'W' . $row['week'] . '/' . $row['year'],
        'revenue' => (float) $row['revenue']
      ];
    }

    // Doanh thu tháng trước
    $lastMonthQuery = "SELECT COALESCE(SUM(total_amount), 0) as revenue_last_month FROM orders WHERE status_id != 5 AND YEAR(created_at) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND MONTH(created_at) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))";
    $lastMonthStmt = $conn->prepare($lastMonthQuery);
    $lastMonthStmt->execute();
    $revenueLastMonth = $lastMonthStmt->fetch()['revenue_last_month'] ?? 0;

    // Tính % thay đổi
    $revenueChangePercent = 0;
    if ($revenueLastMonth > 0) {
      $revenueChangePercent = (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100;
    }

    // Doanh thu theo thời gian được chọn
    $revenueChartData = [];
    if ($chartMonth !== 'all' && $chartMonth !== null) {
        $targetMonth = (int)$chartMonth;
        $targetYear = ($chartYear !== 'all' && $chartYear !== null) ? (int)$chartYear : date('Y');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $targetMonth, $targetYear);
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $key = sprintf('%02d/%02d', $i, $targetMonth);
            $revenueChartData[$key] = [
                'label' => $key,
                'revenue' => 0
            ];
        }
        $chartQuery = "SELECT DAY(created_at) as day, COALESCE(SUM(total_amount), 0) as revenue 
            FROM orders WHERE status_id != 5 AND YEAR(created_at) = $targetYear AND MONTH(created_at) = $targetMonth 
            GROUP BY DAY(created_at)";
        $stmt = $conn->prepare($chartQuery);
        $stmt->execute();
        foreach ($stmt->fetchAll() as $row) {
            $key = sprintf('%02d/%02d', $row['day'], $targetMonth);
            $revenueChartData[$key]['revenue'] = (float)$row['revenue'];
        }
    } else {
        $targetYear = ($chartYear !== 'all' && $chartYear !== null) ? (int)$chartYear : date('Y');
        for ($i = 1; $i <= 12; $i++) {
            $key = "Tháng $i";
            $revenueChartData[$key] = [
                'label' => $key,
                'revenue' => 0
            ];
        }
        $chartQuery = "SELECT MONTH(created_at) as month, COALESCE(SUM(total_amount), 0) as revenue 
            FROM orders WHERE status_id != 5 AND YEAR(created_at) = $targetYear 
            GROUP BY MONTH(created_at)";
        $stmt = $conn->prepare($chartQuery);
        $stmt->execute();
        foreach ($stmt->fetchAll() as $row) {
            $key = "Tháng " . $row['month'];
            $revenueChartData[$key]['revenue'] = (float)$row['revenue'];
        }
    }
    $revenueChartData = array_values($revenueChartData);

    // Top 5 sách bán gần nhất (theo thời gian đặt hàng)
    $topBooksQuery = "SELECT DISTINCT b.book_id, b.title, b.author, b.price, o.created_at, o.order_code, u.full_name AS customer
      FROM books b
      INNER JOIN order_items oi ON oi.book_id = b.book_id
      INNER JOIN orders o ON o.order_id = oi.order_id
      LEFT JOIN users u ON u.user_id = o.user_id
      WHERE o.status_id != 5
      ORDER BY o.created_at DESC
      LIMIT 5";
    $topBooksStmt = $conn->prepare($topBooksQuery);
    $topBooksStmt->execute();
    $topBooks = $topBooksStmt->fetchAll();

    // Sách bán chạy nhất trong tháng hiện tại
    $bestSellersQuery = "SELECT b.book_id, b.title, b.author, b.price, COALESCE(SUM(oi.quantity), 0) AS sold_quantity, COALESCE(SUM(oi.subtotal), 0) AS revenue
      FROM books b
      JOIN order_items oi ON oi.book_id = b.book_id
      JOIN orders o ON o.order_id = oi.order_id
      WHERE o.status_id != 5
        AND YEAR(o.created_at) = YEAR(CURRENT_DATE())
        AND MONTH(o.created_at) = MONTH(CURRENT_DATE())
      GROUP BY b.book_id, b.title, b.author, b.price
      ORDER BY sold_quantity DESC, revenue DESC
      LIMIT 5";
    $bestSellersStmt = $conn->prepare($bestSellersQuery);
    $bestSellersStmt->execute();
    $bestSellers = $bestSellersStmt->fetchAll();

    // Đơn hàng gần đây
    $recentOrdersQuery = "SELECT o.order_code, u.full_name AS customer, o.created_at, o.total_amount, o.discount_amount, os.status_name
      FROM orders o
      LEFT JOIN users u ON u.user_id = o.user_id
      LEFT JOIN order_status os ON os.status_id = o.status_id
      ORDER BY o.created_at DESC
      LIMIT 5";
    $recentOrdersStmt = $conn->prepare($recentOrdersQuery);
    $recentOrdersStmt->execute();
    $recentOrders = $recentOrdersStmt->fetchAll();

    return [
      'total_revenue' => $revenue,
      'total_orders' => $totalOrders,
      'total_books' => $totalBooks,
      'total_customers' => $totalCustomers,
      'revenue_this_month' => $revenueThisMonth,
      'revenue_last_month' => $revenueLastMonth,
      'revenue_change_percent' => round($revenueChangePercent, 2),
      'revenue_today' => $revenueToday,
      'today_orders' => $todayOrders,
      'average_order_value' => $averageOrderValue,
      'pending_orders' => $pendingOrders,
      'new_customers' => $newCustomersThisMonth,
      'low_stock_books' => $lowStockBooks,
      'daily_revenue' => array_values($dailyRevenue),
      'weekly_revenue' => $weeklyRevenue,
      'revenue_chart_data' => $revenueChartData,
      'order_status_distribution' => $orderStatusDistribution,
      'top_books' => $topBooks,
      'best_sellers' => $bestSellers,
      'recent_orders' => $recentOrders,
      'top_customers' => $topCustomers,
      'low_stock_books_list' => $lowStockBooksList,
    ];
  }
}
