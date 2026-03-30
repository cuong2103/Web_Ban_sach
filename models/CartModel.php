<?php
class CartModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // ===== CART =====
    public function findCartIdByUser($userId)
    {
        $stmt = $this->conn->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => (int)$userId]);
        $row = $stmt->fetch();

        if ($row) return (int)$row['cart_id'];

        $this->createCart($userId);
        return (int)$this->conn->lastInsertId();
    }

    public function createCart($userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
        return $stmt->execute(['user_id' => (int)$userId]);
    }

    // ===== CART ITEMS =====
    public function getCartItems($userId)
    {
        $cartId = $this->findCartIdByUser($userId);

        $stmt = $this->conn->prepare("
            SELECT ci.cart_item_id, ci.book_id, ci.quantity, ci.price,
                   b.title, b.thumbnail, b.stock
            FROM cart_items ci
            JOIN books b ON b.book_id = ci.book_id
            WHERE ci.cart_id = :cart_id
        ");
        $stmt->execute(['cart_id' => $cartId]);

        return $stmt->fetchAll();
    }

    public function addToCart($userId, $bookId, $qty = 1)
    {
        $cartId = $this->findCartIdByUser($userId);

        $stmt = $this->conn->prepare("SELECT * FROM cart_items WHERE cart_id = :cart_id AND book_id = :book_id");
        $stmt->execute([
            'cart_id' => $cartId,
            'book_id' => $bookId
        ]);

        $item = $stmt->fetch();

        if ($item) {
            $newQty = $item['quantity'] + $qty;

            $update = $this->conn->prepare("UPDATE cart_items SET quantity = :qty WHERE cart_item_id = :id");
            return $update->execute([
                'qty' => $newQty,
                'id' => $item['cart_item_id']
            ]);
        } else {
            $insert = $this->conn->prepare("
                INSERT INTO cart_items (cart_id, book_id, quantity, price)
                VALUES (:cart_id, :book_id, :quantity, 0)
            ");
            return $insert->execute([
                'cart_id' => $cartId,
                'book_id' => $bookId,
                'quantity' => $qty
            ]);
        }
    }

    public function updateQuantity($userId, $cartItemId, $qty)
    {
        if ($qty <= 0) {
            return $this->removeFromCart($userId, $cartItemId);
        }

        $cartId = $this->findCartIdByUser($userId);

        $stmt = $this->conn->prepare("
            UPDATE cart_items 
            SET quantity = :qty 
            WHERE cart_item_id = :id AND cart_id = :cart_id
        ");
        return $stmt->execute([
            'qty' => $qty,
            'id' => $cartItemId,
            'cart_id' => $cartId
        ]);
    }

    public function removeFromCart($userId, $cartItemId)
    {
        $cartId = $this->findCartIdByUser($userId);

        $stmt = $this->conn->prepare("
            DELETE FROM cart_items 
            WHERE cart_item_id = :id AND cart_id = :cart_id
        ");
        return $stmt->execute([
            'id' => $cartItemId,
            'cart_id' => $cartId
        ]);
    }

    public function clearCart($userId)
    {
        $cartId = $this->findCartIdByUser($userId);

        $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id");
        return $stmt->execute(['cart_id' => $cartId]);
    }

    public function getItemCount($userId)
    {
        $cartId = $this->findCartIdByUser($userId);

        $stmt = $this->conn->prepare("SELECT SUM(quantity) as total FROM cart_items WHERE cart_id = :cart_id");
        $stmt->execute(['cart_id' => $cartId]);

        $row = $stmt->fetch();
        return (int)($row['total'] ?? 0);
    }

    public function getTotalPrice($userId)
    {
        $items = $this->getCartItems($userId);
        $total = 0;

        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    // ===== ORDER =====
    public function createOrder($userId)
    {
        try {
            $this->conn->beginTransaction();

            $cartId = $this->findCartIdByUser($userId);
            $items = $this->getCartItems($userId);

            if (empty($items)) {
                throw new Exception("Giỏ hàng trống");
            }

            $orderCode = 'ORD' . time();
            $total = $this->getTotalPrice($userId);

            $stmt = $this->conn->prepare("
                INSERT INTO orders (user_id, order_code, total_amount)
                VALUES (:user_id, :code, :total)
            ");
            $stmt->execute([
                'user_id' => $userId,
                'code' => $orderCode,
                'total' => $total
            ]);

            $orderId = $this->conn->lastInsertId();

            foreach ($items as $item) {
                $stmt = $this->conn->prepare("
                    INSERT INTO order_items (order_id, book_id, quantity, price)
                    VALUES (:order_id, :book_id, :qty, :price)
                ");
                $stmt->execute([
                    'order_id' => $orderId,
                    'book_id' => $item['book_id'],
                    'qty' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            $this->clearCart($userId);
            $this->conn->commit();

            return ['ok' => true, 'order_id' => $orderId];

        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }
}
?>