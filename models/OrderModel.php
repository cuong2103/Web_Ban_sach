<?php
class OrderModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function generateOrderCode()
    {
        return 'OD' . date('YmdHis') . rand(100, 999);
    }

    public function createOrder($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO orders (user_id, order_code, total_amount, voucher_id, discount_amount, payment_method_id, status_id, shipping_address, phone, note)
            VALUES (:user_id, :order_code, :total_amount, :voucher_id, :discount_amount, :payment_method_id, :status_id, :shipping_address, :phone, :note)");

        $stmt->execute([
            'user_id' => $data['user_id'],
            'order_code' => $data['order_code'],
            'total_amount' => $data['total_amount'],
            'voucher_id' => $data['voucher_id'] ?? null,
            'discount_amount' => $data['discount_amount'] ?? 0,
            'payment_method_id' => $data['payment_method_id'],
            'status_id' => $data['status_id'] ?? 1,
            'shipping_address' => $data['shipping_address'],
            'phone' => $data['phone'],
            'note' => $data['note'] ?? null,
        ]);

        return $this->conn->lastInsertId();
    }

    public function addItems($orderId, $items)
    {
        $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, book_id, quantity, price, subtotal)
            VALUES (:order_id, :book_id, :quantity, :price, :subtotal)");

        foreach ($items as $item) {
            $stmt->execute([
                'order_id' => $orderId,
                'book_id' => $item['book_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        return true;
    }

    public function getOrdersByUser($userId)
    {
        $stmt = $this->conn->prepare("SELECT o.*, os.status_name FROM orders o
            JOIN order_status os ON os.status_id = o.status_id
            WHERE o.user_id = :user_id
            ORDER BY o.created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getOrderById($orderId)
    {
        $stmt = $this->conn->prepare("SELECT o.*, os.status_name FROM orders o
            JOIN order_status os ON os.status_id = o.status_id
            WHERE o.order_id = :order_id LIMIT 1");
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetch();
    }

    public function getOrderItems($orderId)
    {
        $stmt = $this->conn->prepare("SELECT oi.*, b.title, b.thumbnail FROM order_items oi
            JOIN books b ON b.book_id = oi.book_id
            WHERE oi.order_id = :order_id");
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }
}