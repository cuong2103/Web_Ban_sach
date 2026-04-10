<?php
class AdminVoucherController
{
  private $voucherModel;

  public function __construct()
  {
    $this->voucherModel = new VoucherModel();
    // Validate if logged in
    checkLogin();
    // Check if user is admin (role_id = 1)
    $user = $_SESSION['currentUser'] ?? [];
    $role = $user['roles'] ?? $user['role_id'] ?? 2;
    if ((int)$role !== 1) {
      redirect('403');
    }
  }

  public function list()
  {
    $vouchers = $this->voucherModel->getAll();
    require_once './views/admin/vouchers/list.php';
  }

  public function create()
  {
    $voucher = null;
    require_once './views/admin/vouchers/form.php';
  }

  public function edit()
  {
    $id = $_GET['id'] ?? null;
    if (!$id) {
      redirect('admin-vouchers');
    }

    $voucher = $this->voucherModel->findById($id);
    if (!$voucher) {
      Message::set('error', 'Voucher không tồn tại.');
      redirect('admin-vouchers');
    }

    require_once './views/admin/vouchers/form.php';
  }

  public function save()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('admin-vouchers');
    }

    $id = $_POST['voucher_id'] ?? null;
    
    $data = [
      'code' => strtoupper(trim($_POST['code'] ?? '')),
      'discount_type' => $_POST['discount_type'] ?? 'percent',
      'discount_value' => (float)($_POST['discount_value'] ?? 0),
      'max_discount' => isset($_POST['max_discount']) && trim($_POST['max_discount']) !== '' ? (float)$_POST['max_discount'] : '',
      'min_order_value' => (float)($_POST['min_order_value'] ?? 0),
      'start_date' => $_POST['start_date'] ?? date('Y-m-d H:i:s'),
      'end_date' => isset($_POST['end_date']) && trim($_POST['end_date']) !== '' ? $_POST['end_date'] : '',
      'status' => isset($_POST['status']) ? (int)$_POST['status'] : 1
    ];

    if ($data['code'] === '') {
        Message::set('error', 'Mã voucher không được để trống.');
        // We could theoretically redirect back to form, but let's keep it simple
        redirect('admin-vouchers');
    }

    if ($id) {
       // Update
       $result = $this->voucherModel->update($id, $data);
       if ($result !== false) {
         Message::set('success', 'Cập nhật voucher thành công!');
         redirect('admin-vouchers');
       }
    } else {
       // Create
       $result = $this->voucherModel->create($data);
       if ($result !== false) {
         Message::set('success', 'Thêm voucher thành công!');
         redirect('admin-vouchers');
       }
    }
    
    Message::set('error', 'Có lỗi xảy ra, vui lòng thử lại. Mã voucher có thể bị trùng.');
    redirect('admin-vouchers');
  }

  public function delete()
  {
    $id = $_GET['id'] ?? null;
    if ($id) {
      $result = $this->voucherModel->delete($id);
      if ($result) {
         Message::set('success', 'Xóa voucher thành công!');
      } else {
         Message::set('error', 'Không thể xóa voucher, có thể voucher đã được sử dụng.');
      }
    }
    
    redirect('admin-vouchers');
  }
}
