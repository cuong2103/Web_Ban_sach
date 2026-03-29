<?php
class CartModel
{
    private $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function findCartIdByUser($userId)
    {
        $stmt = $this->conn->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => (int) $userId]);
        $row = $stmt->fetch();

        return (int) ($row['cart_id'] ?? 0);
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

        return (int) ($row['total_qty'] ?? 0);
    }

    public function createCartForUser($userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
        $stmt->execute(['user_id' => (int) $userId]);
        return $this->conn->lastInsertId();
    }

    public function ensureCart($userId)
    {
        $cartId = $this->findCartIdByUser($userId);
        if ($cartId <= 0) {
            $cartId = $this->createCartForUser($userId);
        }
        return (int) $cartId;
    }

    public function addToCart($userId, $bookId, $quantity = 1, $price = null)
    {
        $cartId = $this->ensureCart($userId);
        $bookId = (int) $bookId;
        $quantity = max(1, (int) $quantity);

        $stmt = $this->conn->prepare("SELECT quantity FROM cart_items WHERE cart_id = :cart_id AND book_id = :book_id LIMIT 1");
        $stmt->execute(['cart_id' => $cartId, 'book_id' => $bookId]);
        $existing = $stmt->fetch();

        if (!$price) {
            $bookStmt = $this->conn->prepare("SELECT COALESCE(sale_price, price) as selected_price FROM books WHERE book_id = :book_id LIMIT 1");
            $bookStmt->execute(['book_id' => $bookId]);
            $book = $bookStmt->fetch();
            if (!$book) {
                return false;
            }
            $price = $book['selected_price'];
        }

        if ($existing) {
            $newQuantity = $existing['quantity'] + $quantity;
            $update = $this->conn->prepare("UPDATE cart_items SET quantity = :quantity, price = :price WHERE cart_id = :cart_id AND book_id = :book_id");
            return $update->execute(['quantity' => $newQuantity, 'price' => $price, 'cart_id' => $cartId, 'book_id' => $bookId]);
        }

        $insert = $this->conn->prepare("INSERT INTO cart_items (cart_id, book_id, quantity, price) VALUES (:cart_id, :book_id, :quantity, :price)");
        return $insert->execute(['cart_id' => $cartId, 'book_id' => $bookId, 'quantity' => $quantity, 'price' => $price]);
    }

    public function getItems($userId)
    {
        $cartId = $this->findCartIdByUser($userId);
        if ($cartId <= 0) {
            return [];
        }

        $stmt = $this->conn->prepare("SELECT ci.cart_item_id, ci.book_id, ci.quantity, ci.price, b.title, b.thumbnail, b.stock,
            (ci.price * ci.quantity) as subtotal
            FROM cart_items ci
            JOIN books b ON b.book_id = ci.book_id
            WHERE ci.cart_id = :cart_id");
        $stmt->execute(['cart_id' => $cartId]);
        return $stmt->fetchAll();
    }

    public function getTotal($userId)
    {
        $cartId = $this->findCartIdByUser($userId);
        if ($cartId <= 0) {
            return 0;
        }

        $stmt = $this->conn->prepare("SELECT COALESCE(SUM(quantity * price), 0) as total FROM cart_items WHERE cart_id = :cart_id");
        $stmt->execute(['cart_id' => $cartId]);
        $row = $stmt->fetch();
        return (float) ($row['total'] ?? 0);
    }

    public function clearCart($userId)
    {
        $cartId = $this->findCartIdByUser($userId);
        if ($cartId <= 0) {
            return true;
        }

        $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id");
        return $stmt->execute(['cart_id' => $cartId]);
    }

    public function removeItem($userId, $bookId)
    {
        $cartId = $this->findCartIdByUser($userId);
        if ($cartId <= 0) {
            return false;
        }

        $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id AND book_id = :book_id");
        return $stmt->execute(['cart_id' => $cartId, 'book_id' => (int) $bookId]);
    }
}
?>