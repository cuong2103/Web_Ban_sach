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
     * Thêm sản phẩm vào giỏ (API hoặc Form submission hoặc Quick add)
     */
    public function add()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['currentUser'])) {
            Message::set('error', 'Vui lòng đăng nhập để thêm vào giỏ hàng.');
            redirect('login');
        }

        $userId = $_SESSION['currentUser']['id'];
        // Support 'id' from GET (quick add) or POST (form), and 'book_id' (AJAX)
        $bookId = (int) ($_POST['id'] ?? $_POST['book_id'] ?? $_GET['id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? $_GET['quantity'] ?? 1);
        $buyNow = isset($_POST['buy_now']) && $_POST['buy_now'] == '1';

        // Validate input
        if (!$bookId || $quantity < 1) {
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            } else {
                Message::set('error', 'Dữ liệu không hợp lệ.');
                redirect('books');
            }
            exit;
        }

        // Thêm vào giỏ
        $result = $this->cartModel->addToCart($userId, $bookId, $quantity);

        if ($result) {
            $itemCount = $this->cartModel->getItemCount($userId);
            
            // If AJAX request, return JSON
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Đã thêm vào giỏ hàng',
                    'itemCount' => $itemCount
                ]);
            } else {
                // Form submission or quick add - redirect
                Message::set('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
                if ($buyNow) {
                    redirect('cart');
                } else {
                    redirect('cart');
                }
            }
        } else {
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Không thể thêm vào giỏ hàng']);
            } else {
                Message::set('error', 'Không thể thêm sản phẩm vào giỏ hàng.');
                redirect('books');
            }
        }
        exit;
    }

    /**
     * Check if request is AJAX
     */
    private function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
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
