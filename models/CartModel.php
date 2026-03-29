<?php
class CartModel
{
    private $conn;
    public function __construct()
    {
        $this->conn = connectDB();
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

        $stmt = $this->conn->prepare("SELECT COALESCE(SUM(quantity), 0) as total_qty FROM cart_items WHERE cart_id = :cart_id");
        $stmt->execute(['cart_id' => $cartId]);
        $row = $stmt->fetch();

        return (int) ($row['total_qty'] ?? 0);
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