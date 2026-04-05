<?php
class DashboardController
{
  public function Dashboard()
  {
    // TODO: Bật lại khi làm module auth
    // requireAdmin();
    $currentUser = $_SESSION['currentUser'] ?? [];
    $fullname = $currentUser['fullname'] ?? 'Admin';

    // Lấy thống kê
    $stats = $this->getDashboardStats();

    require_once './views/admin/dashboard.php';
  }

  private function getDashboardStats()
  {
    $conn = connectDB();

    // Tổng doanh thu (tính tất cả các đơn, chỉ loại trừ đơn đã hủy)
    $revenueQuery = "SELECT COALESCE(SUM(total_amount - discount_amount), 0) as total_revenue FROM orders WHERE status_id != 5";
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

    // Doanh thu theo tháng (12 tháng gần nhất)
    $monthlyRevenueQuery = "SELECT 
        YEAR(created_at) as year, 
        MONTH(created_at) as month, 
        COALESCE(SUM(total_amount - discount_amount), 0) as revenue 
      FROM orders 
      WHERE status_id != 5 
      GROUP BY YEAR(created_at), MONTH(created_at) 
      ORDER BY YEAR(created_at), MONTH(created_at)";
    $monthlyStmt = $conn->prepare($monthlyRevenueQuery);
    $monthlyStmt->execute();
    $monthlyRevenueRows = $monthlyStmt->fetchAll();

    $monthlyRevenue = [];
    for ($i = 11; $i >= 0; $i--) {
      $date = new DateTime("first day of -{$i} month");
      $key = $date->format('Y-m');
      $monthlyRevenue[$key] = [
        'year' => (int) $date->format('Y'),
        'month' => (int) $date->format('m'),
        'revenue' => 0
      ];
    }

    foreach ($monthlyRevenueRows as $row) {
      $key = sprintf('%04d-%02d', $row['year'], $row['month']);
      if (isset($monthlyRevenue[$key])) {
        $monthlyRevenue[$key]['revenue'] = (float) $row['revenue'];
      }
    }

    // Top 5 sách bán chạy theo số lượng bán
    $topBooksQuery = "SELECT b.book_id, b.title, b.author, b.price, COALESCE(SUM(oi.quantity), 0) AS sold
      FROM books b
      LEFT JOIN order_items oi ON oi.book_id = b.book_id
      GROUP BY b.book_id, b.title, b.author, b.price
      ORDER BY sold DESC
      LIMIT 5";
    $topBooksStmt = $conn->prepare($topBooksQuery);
    $topBooksStmt->execute();
    $topBooks = $topBooksStmt->fetchAll();

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
      'monthly_revenue' => array_values($monthlyRevenue),
      'top_books' => $topBooks,
      'recent_orders' => $recentOrders
    ];
  }
}
