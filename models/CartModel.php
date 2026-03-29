<?php
class CartModel
{
  private $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  public function getOrCreateCartId($userId)
  {
    $stmt = $this->conn->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id LIMIT 1");
    $stmt->execute(['user_id' => (int)$userId]);
    $cart = $stmt->fetch();

    if (!empty($cart['cart_id'])) {
      return (int)$cart['cart_id'];
    }

    $insert = $this->conn->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
    $insert->execute(['user_id' => (int)$userId]);

    return (int)$this->conn->lastInsertId();
  }

  public function addItem($userId, $bookId, $qty = 1)
  {
    $cartId = $this->getOrCreateCartId($userId);
    $qty = max(1, (int)$qty);

    $book = $this->getBookForCart($bookId);
    if (!$book) {
      return ['ok' => false, 'message' => 'Sách không tồn tại hoặc đã ngừng bán.'];
    }

    if ((int)$book['stock'] <= 0) {
      return ['ok' => false, 'message' => 'Sách hiện đã hết hàng.'];
    }

    $currentItem = $this->findCartItem($cartId, $bookId);
    $currentQty = (int)($currentItem['quantity'] ?? 0);
    $newQty = $currentQty + $qty;

    if ($newQty > (int)$book['stock']) {
      return ['ok' => false, 'message' => 'Số lượng vượt quá tồn kho hiện tại.'];
    }

    if ($currentItem) {
      $stmt = $this->conn->prepare("UPDATE cart_items SET quantity = :quantity, price = :price WHERE cart_id = :cart_id AND book_id = :book_id");
      $stmt->execute([
        'quantity' => $newQty,
        'price' => $this->getEffectivePrice($book),
        'cart_id' => $cartId,
        'book_id' => (int)$bookId,
      ]);
    } else {
      $stmt = $this->conn->prepare("INSERT INTO cart_items (cart_id, book_id, quantity, price) VALUES (:cart_id, :book_id, :quantity, :price)");
      $stmt->execute([
        'cart_id' => $cartId,
        'book_id' => (int)$bookId,
        'quantity' => $qty,
        'price' => $this->getEffectivePrice($book),
      ]);
    }

    return ['ok' => true, 'message' => 'Đã thêm sách vào giỏ hàng.'];
  }

  public function updateItemQuantity($userId, $bookId, $quantity)
  {
    $cartId = $this->getOrCreateCartId($userId);
    $quantity = (int)$quantity;

    if ($quantity <= 0) {
      return $this->removeItem($userId, $bookId);
    }

    $book = $this->getBookForCart($bookId);
    if (!$book) {
      return ['ok' => false, 'message' => 'Sách không tồn tại hoặc đã ngừng bán.'];
    }

    if ($quantity > (int)$book['stock']) {
      return ['ok' => false, 'message' => 'Số lượng vượt quá tồn kho hiện tại.'];
    }

    $stmt = $this->conn->prepare("UPDATE cart_items SET quantity = :quantity, price = :price WHERE cart_id = :cart_id AND book_id = :book_id");
    $stmt->execute([
      'quantity' => $quantity,
      'price' => $this->getEffectivePrice($book),
      'cart_id' => $cartId,
      'book_id' => (int)$bookId,
    ]);

    return ['ok' => true, 'message' => 'Đã cập nhật số lượng sản phẩm.'];
  }

  public function removeItem($userId, $bookId)
  {
    $cartId = $this->getOrCreateCartId($userId);
    $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id AND book_id = :book_id");
    $stmt->execute([
      'cart_id' => $cartId,
      'book_id' => (int)$bookId,
    ]);

    return ['ok' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.'];
  }

  public function getCartItems($userId)
  {
    $cartId = $this->getOrCreateCartId($userId);

    $stmt = $this->conn->prepare("
      SELECT 
        ci.book_id,
        ci.quantity,
        ci.price as unit_price,
        b.title,
        b.author,
        b.thumbnail,
        b.stock,
        b.status
      FROM cart_items ci
      INNER JOIN books b ON b.book_id = ci.book_id
      WHERE ci.cart_id = :cart_id
      ORDER BY ci.cart_item_id DESC
    ");
    $stmt->execute(['cart_id' => $cartId]);
    $items = $stmt->fetchAll();

    foreach ($items as &$item) {
      if ((int)$item['status'] !== 1) {
        $item['is_available'] = false;
      } else {
        $item['is_available'] = true;
      }
      $item['line_total'] = (float)$item['unit_price'] * (int)$item['quantity'];
    }

    return $items;
  }

  public function getItemCount($userId)
  {
    $cartId = $this->findCartIdByUser($userId);
    if ($cartId <= 0) {
      return 0;
    }

    $stmt = $this->conn->prepare("SELECT COALESCE(SUM(quantity), 0) as total_qty FROM cart_items WHERE cart_id = :cart_id");
    $stmt->execute(['cart_id' => $cartId]);
    $row = $stmt->fetch();

    return (int)($row['total_qty'] ?? 0);
  }

  public function calculateTotals($userId, $voucher = null)
  {
    $items = $this->getCartItems($userId);

    $subtotal = 0;
    $itemCount = 0;

    foreach ($items as $item) {
      if (!$item['is_available']) {
        continue;
      }
      $subtotal += $item['line_total'];
      $itemCount += (int)$item['quantity'];
    }

    $shippingFee = $subtotal > 0 ? 0 : 0;
    $discount = 0;

    if (!empty($voucher) && !empty($voucher['is_valid'])) {
      if ($voucher['discount_type'] === 'percent') {
        $discount = $subtotal * ((float)$voucher['discount_value'] / 100);
        if (!empty($voucher['max_discount'])) {
          $discount = min($discount, (float)$voucher['max_discount']);
        }
      } else {
        $discount = (float)$voucher['discount_value'];
      }
      $discount = min($discount, $subtotal);
    }

    $total = max(0, $subtotal + $shippingFee - $discount);

    return [
      'item_count' => $itemCount,
      'subtotal' => $subtotal,
      'shipping_fee' => $shippingFee,
      'discount' => $discount,
      'total' => $total,
    ];
  }

  public function validateVoucher($code, $subtotal)
  {
    $cleanCode = strtoupper(trim((string)$code));
    if ($cleanCode === '') {
      return [
        'is_valid' => false,
        'message' => 'Vui lòng nhập mã giảm giá.',
      ];
    }

    $stmt = $this->conn->prepare("
      SELECT 
        voucher_id,
        code,
        discount_type,
        discount_value,
        max_discount,
        min_order_value,
        start_date,
        end_date,
        status
      FROM vouchers
      WHERE UPPER(code) = :code
      LIMIT 1
    ");
    $stmt->execute(['code' => $cleanCode]);
    $voucher = $stmt->fetch();

    if (!$voucher) {
      return [
        'is_valid' => false,
        'message' => 'Mã giảm giá không tồn tại.',
      ];
    }

    if ((int)$voucher['status'] !== 1) {
      return [
        'is_valid' => false,
        'message' => 'Mã giảm giá hiện không khả dụng.',
      ];
    }

    $now = time();
    $start = strtotime($voucher['start_date']);
    $end = strtotime($voucher['end_date']);

    if ($start && $now < $start) {
      return [
        'is_valid' => false,
        'message' => 'Mã giảm giá chưa đến thời gian áp dụng.',
      ];
    }

    if ($end && $now > $end) {
      return [
        'is_valid' => false,
        'message' => 'Mã giảm giá đã hết hạn.',
      ];
    }

    if ((float)$subtotal < (float)$voucher['min_order_value']) {
      return [
        'is_valid' => false,
        'message' => 'Đơn hàng chưa đạt giá trị tối thiểu để dùng mã.',
      ];
    }

    /**
     * Tìm ID giỏ hàng của người dùng, nếu không có thì tạo mới
     */
    public function findCartIdByUser($userId)
    {
        $stmt = $this->conn->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => (int) $userId]);
        $row = $stmt->fetch();

        if ($row) {
            return (int) $row['cart_id'];
        }

        // Tạo giỏ hàng mới nếu chưa có
        $this->createCart($userId);
        $stmt = $this->conn->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => (int) $userId]);
        $row = $stmt->fetch();

        return (int) ($row['cart_id'] ?? 0);
    }

    /**
     * Tạo giỏ hàng mới
     */
    public function createCart($userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
        return $stmt->execute(['user_id' => (int) $userId]);
    }

    /**
     * Lấy số lượng sản phẩm trong giỏ
     */
    public function getItemCount($userId)
    {
        $cartId = $this->findCartIdByUser($userId);
        if ($cartId <= 0) {
            return 0;
        }
      }

      if (!empty($voucher['voucher_id'])) {
        $voucherUsageStmt = $this->conn->prepare("\n          INSERT INTO voucher_usages (voucher_id, user_id, order_id)\n          VALUES (:voucher_id, :user_id, :order_id)\n        ");
        $voucherUsageStmt->execute([
          'voucher_id' => (int)$voucher['voucher_id'],
          'user_id' => (int)$userId,
          'order_id' => $orderId,
        ]);
      }

      $clearCartStmt = $this->conn->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id");
      $clearCartStmt->execute(['cart_id' => $cartId]);

      $this->conn->commit();

      return [
        'ok' => true,
        'order_id' => $orderId,
        'order_code' => $orderCode,
        'total' => (float)$totals['total'],
      ];
    } catch (Exception $e) {
      if ($this->conn->inTransaction()) {
        $this->conn->rollBack();
      }

      return [
        'ok' => false,
        'message' => $e->getMessage() ?: 'Không thể tạo đơn hàng lúc này.',
      ];
    }
  }

  private function generateOrderCode()
  {
    return 'ORD' . date('YmdHis') . rand(100, 999);
  }

  public function getOrderHistory($userId)
  {
    $stmt = $this->conn->prepare("\n      SELECT\n        o.order_id,\n        o.order_code,\n        o.total_amount,\n        o.discount_amount,\n        o.created_at,\n        o.status_id,\n        s.status_name,\n        COUNT(oi.order_item_id) AS item_count\n      FROM orders o\n      LEFT JOIN order_status s ON s.status_id = o.status_id\n      LEFT JOIN order_items oi ON oi.order_id = o.order_id\n      WHERE o.user_id = :user_id\n      GROUP BY o.order_id, o.order_code, o.total_amount, o.discount_amount, o.created_at, o.status_id, s.status_name\n      ORDER BY o.created_at DESC\n    ");
    $stmt->execute(['user_id' => (int)$userId]);

    return $stmt->fetchAll();
  }

  public function cancelOrder($userId, $orderCode)
  {
    try {
      $this->conn->beginTransaction();

      $stmt = $this->conn->prepare("SELECT order_id, status_id FROM orders WHERE user_id = :user_id AND order_code = :order_code LIMIT 1 FOR UPDATE");
      $stmt->execute([
        'user_id' => (int)$userId,
        'order_code' => $orderCode,
      ]);
      $order = $stmt->fetch();

      if (!$order) {
        throw new Exception("Đơn hàng không tồn tại.");
      }

      if ((int)$order['status_id'] !== 1) {
        throw new Exception("Chỉ có thể hủy đơn hàng đang ở trạng thái 'Chờ xử lý'.");
      }

      $updateStmt = $this->conn->prepare("UPDATE orders SET status_id = 5 WHERE order_id = :order_id");
      $updateStmt->execute(['order_id' => $order['order_id']]);

      $itemsStmt = $this->conn->prepare("SELECT book_id, quantity FROM order_items WHERE order_id = :order_id");
      $itemsStmt->execute(['order_id' => $order['order_id']]);
      $items = $itemsStmt->fetchAll();

      $restockStmt = $this->conn->prepare("UPDATE books SET stock = stock + :quantity WHERE book_id = :book_id");
      foreach ($items as $item) {
        $restockStmt->execute([
          'quantity' => (int)$item['quantity'],
          'book_id' => (int)$item['book_id'],
        ]);
      }

      $this->conn->commit();
      return ['ok' => true, 'message' => 'Hủy đơn hàng thành công.'];
    } catch (Exception $e) {
      if ($this->conn->inTransaction()) {
        $this->conn->rollBack();
      }
      return ['ok' => false, 'message' => $e->getMessage()];
    }

    /**
     * Lấy tất cả sản phẩm trong giỏ
     */
    public function getCartItems($userId)
    {
        $cartId = $this->findCartIdByUser($userId);
        if ($cartId <= 0) {
            return [];
        }

        $query = "
            SELECT 
                ci.cart_item_id,
                ci.book_id,
                ci.quantity,
                ci.price,
                b.title,
                b.author,
                b.thumbnail,
                b.stock,
                b.sale_price,
                b.price as original_price
            FROM cart_items ci
            INNER JOIN books b ON ci.book_id = b.book_id
            WHERE ci.cart_id = :cart_id
            ORDER BY ci.cart_item_id DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['cart_id' => $cartId]);

        return $stmt->fetchAll();
    }

    /**
     * Thêm sản phẩm vào giỏ
     */
    public function addToCart($userId, $bookId, $quantity = 1)
    {
        try {
            $cartId = $this->findCartIdByUser($userId);
            if ($cartId <= 0) {
                return false;
            }

            // Lấy giá sách hiện tại
            $bookStmt = $this->conn->prepare("SELECT sale_price, price FROM books WHERE book_id = :book_id");
            $bookStmt->execute(['book_id' => (int) $bookId]);
            $book = $bookStmt->fetch();

            if (!$book) {
                return false;
            }

            $price = $book['sale_price'] ?? $book['price'];

            // Kiểm tra sản phẩm đã có trong giỏ chưa
            $checkStmt = $this->conn->prepare("
                SELECT cart_item_id, quantity FROM cart_items 
                WHERE cart_id = :cart_id AND book_id = :book_id
            ");
            $checkStmt->execute(['cart_id' => $cartId, 'book_id' => (int) $bookId]);
            $existingItem = $checkStmt->fetch();

            if ($existingItem) {
                // Update số lượng
                $newQuantity = $existingItem['quantity'] + (int) $quantity;
                $updateStmt = $this->conn->prepare("
                    UPDATE cart_items 
                    SET quantity = :quantity 
                    WHERE cart_item_id = :cart_item_id
                ");
                return $updateStmt->execute([
                    'quantity' => $newQuantity,
                    'cart_item_id' => $existingItem['cart_item_id']
                ]);
            } else {
                // Insert sản phẩm mới
                $stmt = $this->conn->prepare("
                    INSERT INTO cart_items (cart_id, book_id, quantity, price) 
                    VALUES (:cart_id, :book_id, :quantity, :price)
                ");
                return $stmt->execute([
                    'cart_id' => $cartId,
                    'book_id' => (int) $bookId,
                    'quantity' => (int) $quantity,
                    'price' => $price
                ]);
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Xoá sản phẩm khỏi giỏ
     */
    public function removeFromCart($userId, $cartItemId)
    {
        try {
            $cartId = $this->findCartIdByUser($userId);
            if ($cartId <= 0) {
                return false;
            }

            $stmt = $this->conn->prepare("
                DELETE FROM cart_items 
                WHERE cart_item_id = :cart_item_id AND cart_id = :cart_id
            ");
            return $stmt->execute([
                'cart_item_id' => (int) $cartItemId,
                'cart_id' => $cartId
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Cập nhật số lượng sản phẩm
     */
    public function updateQuantity($userId, $cartItemId, $quantity)
    {
        try {
            $quantity = (int) $quantity;
            if ($quantity <= 0) {
                return $this->removeFromCart($userId, $cartItemId);
            }

            $cartId = $this->findCartIdByUser($userId);
            if ($cartId <= 0) {
                return false;
            }

            $stmt = $this->conn->prepare("
                UPDATE cart_items 
                SET quantity = :quantity 
                WHERE cart_item_id = :cart_item_id AND cart_id = :cart_id
            ");
            return $stmt->execute([
                'quantity' => $quantity,
                'cart_item_id' => (int) $cartItemId,
                'cart_id' => $cartId
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Xoá toàn bộ giỏ hàng
     */
    public function clearCart($userId)
    {
        try {
            $cartId = $this->findCartIdByUser($userId);
            if ($cartId <= 0) {
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id");
            return $stmt->execute(['cart_id' => $cartId]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Tính tổng tiền giỏ hàng
     */
    public function getTotalPrice($userId)
    {
        $items = $this->getCartItems($userId);
        $total = 0;

        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }
}
?>
