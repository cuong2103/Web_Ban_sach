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
}
?>