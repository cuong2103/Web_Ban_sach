<?php
class OrderController
{
    private $orderModel;
    private $cartModel;
    private $bookModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->cartModel = new CartModel();
        $this->bookModel = new BookModel();
    }

    public function checkout()
    {
        checkLogin();
        $currentUser = $_SESSION['currentUser'];
        $items = $this->cartModel->getItems($currentUser['id']);
        $total = $this->cartModel->getTotal($currentUser['id']);

        if (empty($items)) {
            Message::set('error', 'Giỏ hàng trống, không thể thực hiện thanh toán.');
            redirect('cart');
        }

        $userModel = new UserModel();
        $userInfo = $userModel->findById($currentUser['id']);

        require_once './views/customer/checkout.php';
    }

    public function create()
    {
        checkLogin();
        $currentUser = $_SESSION['currentUser'];

        $address = trim($_POST['shipping_address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $note = trim($_POST['note'] ?? '');

        $errors = validate(['shipping_address' => $address, 'phone' => $phone], ['shipping_address' => 'required', 'phone' => 'required|phone']);

        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            Message::set('error', 'Vui lòng điền đầy đủ thông tin giao nhận hợp lệ.');
            redirect('checkout');
        }

        $items = $this->cartModel->getItems($currentUser['id']);
        if (empty($items)) {
            Message::set('error', 'Giỏ hàng trống, không thể tạo đơn hàng.');
            redirect('cart');
        }

        $total = 0;
        foreach ($items as $item) {
            if ($item['quantity'] > $item['stock']) {
                Message::set('error', "Sản phẩm '{$item['title']}' không đủ số lượng.");
                redirect('cart');
            }
            $total += (float)$item['subtotal'];
        }

        $orderData = [
            'user_id' => $currentUser['id'],
            'order_code' => $this->orderModel->generateOrderCode(),
            'total_amount' => $total,
            'voucher_id' => null,
            'discount_amount' => 0,
            'payment_method_id' => 1,
            'status_id' => 1,
            'shipping_address' => $address,
            'phone' => $phone,
            'note' => $note,
        ];

        $orderId = $this->orderModel->createOrder($orderData);

        $orderItems = [];
        foreach ($items as $item) {
            $orderItems[] = [
                'book_id' => $item['book_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ];
            // giảm tồn kho đối với sách
            $this->bookModel->decreaseStock($item['book_id'], $item['quantity']);
        }

        $this->orderModel->addItems($orderId, $orderItems);
        $this->cartModel->clearCart($currentUser['id']);

        Message::set('success', 'Đơn hàng được tạo thành công. Mã đơn: ' . $orderData['order_code']);
        redirect('orders');
    }

    public function index()
    {
        checkLogin();
        $currentUser = $_SESSION['currentUser'];
        $orders = $this->orderModel->getOrdersByUser($currentUser['id']);
        require_once './views/customer/orders.php';
    }

    public function detail()
    {
        checkLogin();
        $orderId = (int) ($_GET['id'] ?? 0);
        if (!$orderId) {
            redirect('orders');
        }

        $currentUser = $_SESSION['currentUser'];
        $order = $this->orderModel->getOrderById($orderId);

        if (!$order || $order['user_id'] != $currentUser['id']) {
            Message::set('error', 'Đơn hàng không tồn tại hoặc không có quyền xem.');
            redirect('orders');
        }

        $items = $this->orderModel->getOrderItems($orderId);
        require_once './views/customer/order_detail.php';
    }
}
