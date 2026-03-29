<?php
class CartController
{
    private $cartModel;
    private $bookModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->bookModel = new BookModel();
    }

    public function view()
    {
        checkLogin();
        $currentUser = $_SESSION['currentUser'];
        $items = $this->cartModel->getItems($currentUser['id']);
        $total = $this->cartModel->getTotal($currentUser['id']);
        require_once './views/customer/cart.php';
    }

    public function add()
    {
        checkLogin();

        $currentUser = $_SESSION['currentUser'];
        $bookId = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);
        $buyNow = isset($_POST['buy_now']) && $_POST['buy_now'] == '1';

        if ($bookId <= 0 || $quantity <= 0) {
            Message::set('error', 'Dữ liệu không hợp lệ.');
            redirect('books');
        }

        $book = $this->bookModel->getById($bookId);
        if (!$book) {
            Message::set('error', 'Sản phẩm không tồn tại.');
            redirect('books');
        }

        if ($book['stock'] < $quantity) {
            Message::set('error', 'Số lượng đặt vượt quá tồn kho.');
            redirect('books');
        }

        $price = $book['sale_price'] > 0 ? $book['sale_price'] : $book['price'];
        $this->cartModel->addToCart($currentUser['id'], $bookId, $quantity, $price);

        Message::set('success', 'Thêm vào giỏ hàng thành công.');

        if ($buyNow) {
            redirect('checkout');
        }

        redirect('cart');
    }

    public function remove()
    {
        checkLogin();
        $currentUser = $_SESSION['currentUser'];
        $bookId = (int) ($_GET['id'] ?? 0);

        if ($bookId <= 0) {
            Message::set('error', 'Không tìm thấy sản phẩm.');
            redirect('cart');
        }

        $this->cartModel->removeItem($currentUser['id'], $bookId);
        Message::set('success', 'Xóa sản phẩm khỏi giỏ hàng thành công.');
        redirect('cart');
    }
}