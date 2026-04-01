<?php
class AdminUserController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new UserModel();
  }

  public function list()
  {
    $users = $this->userModel->getAll();
    require_once './views/admin/users/index.php';
  }

  public function create()
  {
    require_once './views/admin/users/create.php';
  }

  public function store()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('admin-users');
    }

    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['roles'] ?? 'customer';
    $status   = (int)($_POST['status'] ?? 1);

    $_SESSION['old'] = $_POST;

    $errors = [];

    // Validate required fields
    if (empty($fullname) || mb_strlen($fullname) < 3) {
      $errors['fullname'] = 'Họ và tên phải có ít nhất 3 ký tự.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Email không hợp lệ.';
    }
    if (empty($password) || strlen($password) < 8) {
      $errors['password'] = 'Mật khẩu phải có ít nhất 8 ký tự.';
    }

    // Check email uniqueness
    if (!isset($errors['email'])) {
      $existingEmail = $this->userModel->findByEmail($email);
      if ($existingEmail) {
        $errors['email'] = 'Email này đã tồn tại trong hệ thống.';
      }
    }

    // Check phone format (10 digits) and uniqueness
    if (!empty($phone)) {
      if (!preg_match('/^\d{10}$/', $phone)) {
        $errors['phone'] = 'Số điện thoại phải gồm đúng 10 chữ số.';
      } else {
        $existingPhone = $this->userModel->findByPhone($phone);
        if ($existingPhone) {
          $errors['phone'] = 'Số điện thoại này đã được sử dụng bởi tài khoản khác.';
        }
      }
    }

    // Check password confirm
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    if (!isset($errors['password']) && $password !== $passwordConfirm) {
      $errors['password_confirm'] = 'Mật khẩu nhập lại không khớp.';
    }

    if (!empty($errors)) {
      $_SESSION['errors'] = $errors;
      redirect('admin-users-create');
    }

    $data = [
      'fullname' => $fullname,
      'email'    => $email,
      'phone'    => $phone,
      'address'  => $address,
      'password' => password_hash($password, PASSWORD_BCRYPT),
      'roles'    => $role,
      'status'   => $status,
      'avatar'   => null
    ];

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
      $file = $_FILES['avatar'];
      $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
      $allowed = ['jpg', 'jpeg', 'png', 'gif'];
      if (in_array(strtolower($ext), $allowed)) {
        $filename = time() . '_' . rand(100, 999) . '.' . $ext;
        if (!is_dir('./uploads/avatars')) mkdir('./uploads/avatars', 0777, true);
        if (move_uploaded_file($file['tmp_name'], './uploads/avatars/' . $filename)) {
          $data['avatar'] = '/uploads/avatars/' . $filename;
        }
      }
    }

    if ($this->userModel->create($data)) {
      unset($_SESSION['old']);
      unset($_SESSION['errors']);
      Message::set('success', 'Thêm tài khoản thành công.');
      redirect('admin-users');
    } else {
      Message::set('error', 'Không thể tạo tài khoản lúc này.');
      redirect('admin-users-create');
    }
  }

  public function edit()
  {
    $id = (int)($_GET['id'] ?? 0);
    $user = $this->userModel->findById($id);

    if (!$user) {
      Message::set('error', 'Không tìm thấy tài khoản.');
      redirect('admin-users');
    }

    require_once './views/admin/users/edit.php';
  }

  public function update()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('admin-users');
    }

    $id = (int)($_POST['id'] ?? 0);
    $user = $this->userModel->findById($id);

    if (!$user) {
      Message::set('error', 'Không tìm thấy tài khoản.');
      redirect('admin-users');
    }

    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $role     = $_POST['roles'] ?? 'customer';
    $status   = (int)($_POST['status'] ?? 0);
    $password = $_POST['password'] ?? '';

    $errors = [];

    if (empty($fullname) || mb_strlen($fullname) < 3) {
      $errors['fullname'] = 'Họ và tên phải có ít nhất 3 ký tự.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Email không hợp lệ.';
    }

    // Check email uniqueness (exclude current user)
    if (!isset($errors['email'])) {
      $existingEmail = $this->userModel->findByEmail($email);
      if ($existingEmail && $existingEmail['id'] != $id) {
        $errors['email'] = 'Email này đã tồn tại ở tài khoản khác.';
      }
    }

    // Check phone format (10 digits) and uniqueness (exclude current user)
    if (!empty($phone)) {
      if (!preg_match('/^\d{10}$/', $phone)) {
        $errors['phone'] = 'Số điện thoại phải gồm đúng 10 chữ số.';
      } else {
        $existingPhone = $this->userModel->findByPhone($phone, $id);
        if ($existingPhone) {
          $errors['phone'] = 'Số điện thoại này đã được sử dụng bởi tài khoản khác.';
        }
      }
    }

    // Validate new password if provided
    if (!empty($password) && strlen($password) < 8) {
      $errors['password'] = 'Mật khẩu mới phải có ít nhất 8 ký tự.';
    }

    // Check password confirm (only if password is being changed)
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    if (!empty($password) && !isset($errors['password']) && $password !== $passwordConfirm) {
      $errors['password_confirm'] = 'Mật khẩu nhập lại không khớp.';
    }


    if (!empty($errors)) {
      $_SESSION['errors'] = $errors;
      redirect('admin-users-edit&id=' . $id);
    }

    $data = [
      'fullname' => $fullname,
      'email'    => $email,
      'phone'    => $phone,
      'address'  => $address,
      'roles'    => $role,
      'status'   => $status,
      'avatar'   => $user['avatar']
    ];

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
      $file = $_FILES['avatar'];
      $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
      $allowed = ['jpg', 'jpeg', 'png', 'gif'];
      if (in_array(strtolower($ext), $allowed)) {
        $filename = time() . '_' . rand(100, 999) . '.' . $ext;
        if (!is_dir('./uploads/avatars')) mkdir('./uploads/avatars', 0777, true);
        if (move_uploaded_file($file['tmp_name'], './uploads/avatars/' . $filename)) {
          // Xóa ảnh cũ nếu tồn tại
          if (!empty($user['avatar'])) {
            $oldPath = '.' . $user['avatar'];
            if (file_exists($oldPath)) {
              unlink($oldPath);
            }
          }
          $data['avatar'] = '/uploads/avatars/' . $filename;
        }
      }
    }

    $this->userModel->update($id, $data);

    if (!empty($password)) {
      $this->userModel->changePassword($id, $password);
    }

    unset($_SESSION['errors']);
    Message::set('success', 'Cập nhật tài khoản thành công.');
    redirect('admin-users');
  }

  public function toggleStatus()
  {
    $id = (int)($_POST['id'] ?? 0);
    $user = $this->userModel->findById($id);

    if ($user) {
      $newStatus = $user['status'] == 1 ? 0 : 1;
      
      $data = [
        'fullname' => $user['fullname'],
        'email'    => $user['email'],
        'phone'    => $user['phone'] ?? null,
        'address'  => $user['address'] ?? null,
        'roles'    => $user['roles'] == 1 ? 'admin' : 'customer',
        'status'   => $newStatus,
        'avatar'   => $user['avatar']
      ];
      $this->userModel->update($id, $data);
      Message::set('success', 'Đổi trạng thái tài khoản thành công.');
    } else {
      Message::set('error', 'Không tìm thấy tài khoản.');
    }
    redirect('admin-users');
  }
}
?>
