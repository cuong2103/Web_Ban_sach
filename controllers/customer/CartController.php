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
        $currentUser = $_SESSION['currentUser'] ?? null;

        if ($currentUser) {
            $items = $this->cartModel->getItems($currentUser['id']);
            $total = $this->cartModel->getTotal($currentUser['id']);
        } else {
            $guestItems = $this->cartModel->getGuestItems();
            $items = [];
            $total = 0;

            foreach ($guestItems as $line) {
                $book = $this->bookModel->getById($line['book_id']);
                if (!$book) {
                    continue;
                }
                $itemTotal = $line['price'] * $line['quantity'];
                $items[] = [
                    'book_id' => $line['book_id'],
                    'title' => $book['title'],
                    'thumbnail' => $book['thumbnail'],
                    'stock' => $book['stock'],
                    'quantity' => $line['quantity'],
                    'price' => $line['price'],
                    'subtotal' => $itemTotal,
                ];
                $total += $itemTotal;
            }
        }

        require_once './views/customer/cart.php';
    }

    public function add()
    {
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

        $currentUser = $_SESSION['currentUser'] ?? null;

        if ($currentUser) {
            $this->cartModel->addToCart($currentUser['id'], $bookId, $quantity, $price);
        } else {
            $this->cartModel->addGuestItem($bookId, $quantity, $price);
        }

        Message::set('success', 'Thêm vào giỏ hàng thành công.');

        if ($buyNow) {
            if (!$currentUser) {
                Message::set('success', 'Bạn cần đăng nhập để tiếp tục mua ngay.');
                redirect('login');
            }
            redirect('checkout');
        }

        redirect('cart');
    }

    public function remove()
    {
        $currentUser = $_SESSION['currentUser'] ?? null;
        $bookId = (int) ($_GET['id'] ?? 0);

        if ($bookId <= 0) {
            Message::set('error', 'Không tìm thấy sản phẩm.');
            redirect('cart');
        }

        if ($currentUser) {
            $this->cartModel->removeItem($currentUser['id'], $bookId);
        } else {
            $this->cartModel->removeGuestItem($bookId);
        }

        Message::set('success', 'Xóa sản phẩm khỏi giỏ hàng thành công.');
        redirect('cart');
    }
}