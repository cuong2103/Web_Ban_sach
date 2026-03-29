<?php
class CartController
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
    }

    /**
     * Xem giỏ hàng
     */
    public function view()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['currentUser'])) {
            Message::set('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
            redirect('login');
        }

        $userId = $_SESSION['currentUser']['id'];
        $cartItems = $this->cartModel->getCartItems($userId);
        $totalPrice = $this->cartModel->getTotalPrice($userId);
        $itemCount = $this->cartModel->getItemCount($userId);

        require_once './views/customer/cart.php';
    }

    /**
     * Thêm sản phẩm vào giỏ (API)
     */
    public function add()
    {
        header('Content-Type: application/json');

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['currentUser'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }

        $userId = $_SESSION['currentUser']['id'];
        $bookId = (int) ($_POST['book_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        // Validate input
        if (!$bookId || $quantity < 1) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        // Thêm vào giỏ
        $result = $this->cartModel->addToCart($userId, $bookId, $quantity);

        if ($result) {
            $itemCount = $this->cartModel->getItemCount($userId);
            echo json_encode([
                'success' => true,
                'message' => 'Đã thêm vào giỏ hàng',
                'itemCount' => $itemCount
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Không thể thêm vào giỏ hàng']);
        }
        exit;
    }

    /**
     * Xoá sản phẩm khỏi giỏ (API)
     */
    public function remove()
    {
        header('Content-Type: application/json');

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['currentUser'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }

        $userId = $_SESSION['currentUser']['id'];
        $cartItemId = (int) ($_POST['cart_item_id'] ?? 0);

        if (!$cartItemId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $result = $this->cartModel->removeFromCart($userId, $cartItemId);

        if ($result) {
            $itemCount = $this->cartModel->getItemCount($userId);
            $totalPrice = $this->cartModel->getTotalPrice($userId);
            echo json_encode([
                'success' => true,
                'message' => 'Sản phẩm đã được xoá',
                'itemCount' => $itemCount,
                'totalPrice' => number_format($totalPrice, 0, ',', '.'),
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Không thể xoá sản phẩm']);
        }
        exit;
    }

    /**
     * Cập nhật số lượng sản phẩm (API)
     */
    public function updateQuantity()
    {
        header('Content-Type: application/json');

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['currentUser'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }

        $userId = $_SESSION['currentUser']['id'];
        $cartItemId = (int) ($_POST['cart_item_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        if (!$cartItemId || $quantity < 1) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $result = $this->cartModel->updateQuantity($userId, $cartItemId, $quantity);

        if ($result) {
            $itemCount = $this->cartModel->getItemCount($userId);
            $totalPrice = $this->cartModel->getTotalPrice($userId);
            $cartItems = $this->cartModel->getCartItems($userId);

            // Lấy item vừa update
            $updatedItem = null;
            foreach ($cartItems as $item) {
                if ($item['cart_item_id'] == $cartItemId) {
                    $updatedItem = $item;
                    break;
                }
            }

            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật số lượng thành công',
                'itemCount' => $itemCount,
                'totalPrice' => number_format($totalPrice, 0, ',', '.'),
                'itemTotal' => number_format($updatedItem['price'] * $updatedItem['quantity'], 0, ',', '.'),
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Không thể cập nhật số lượng']);
        }
        exit;
    }

    /**
     * Xoá toàn bộ giỏ hàng
     */
    public function clear()
    {
        header('Content-Type: application/json');

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['currentUser'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }

        $userId = $_SESSION['currentUser']['id'];
        $result = $this->cartModel->clearCart($userId);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Giỏ hàng đã được xoá',
                'itemCount' => 0,
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Không thể xoá giỏ hàng']);
        }
        exit;
    }
}
?>
