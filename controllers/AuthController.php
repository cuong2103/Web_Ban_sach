<?php
class AuthController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new UserModel();
  }

  public function formLogin()
  {
    require_once './views/auth/login.php';
  }

  public function login()
  {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $_SESSION['old']['email'] = $email;

    $errors = validate(['email' => $email, 'password' => $password], [
      'email'    => 'required|email',
      'password' => 'required',
    ]);

    if (!empty($errors)) {
      Message::set('error', 'Vui lòng điền đầy đủ thông tin hợp lệ.');
      redirect('login');
    }

    $user = $this->userModel->findByEmail($email);

    if (!$user || !password_verify($password, $user['password'])) {
      Message::set('error', 'Email hoặc mật khẩu không đúng.');
      redirect('login');
    }

    if ($user['status'] != 1) {
      Message::set('error', 'Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.');
      redirect('login');
    }

    $_SESSION['currentUser'] = [
      'id'       => $user['id'],
      'fullname' => $user['fullname'],
      'email'    => $user['email'],
      'roles'    => $user['roles'],
      'status'   => $user['status'],
      'avatar'   => $user['avatar'] ?? null,
    ];

    unset($_SESSION['old']);

    Message::set('success', 'Đăng nhập thành công! Chào mừng ' . $user['fullname']);
    redirect('/');
  }

  public function formRegister()
  {
    require_once './views/auth/register.php';
  }

  public function register()
  {
    $fullname           = trim($_POST['fullname'] ?? '');
    $email              = trim($_POST['email'] ?? '');
    $phone              = trim($_POST['phone'] ?? '');
    $password           = $_POST['password'] ?? '';
    $password_confirm   = $_POST['password_confirm'] ?? '';

    $_SESSION['old']['fullname'] = $fullname;
    $_SESSION['old']['email'] = $email;
    $_SESSION['old']['phone'] = $phone;

    $errors = validate([
      'fullname' => $fullname,
      'email' => $email,
      'phone' => $phone,
      'password' => $password,
      'password_confirm' => $password_confirm
    ], [
      'fullname' => 'required|min:3',
      'email' => 'required|email',
      'phone' => 'required|phone',
      'password' => 'required|min:8',
      'password_confirm' => 'required|min:8',
    ]);

    if (!isset($_POST['terms'])) {
      $errors['terms'][] = 'Bạn phải đồng ý với Điều khoản dịch vụ và Chính sách bảo mật.';
    }

    if (!empty($errors)) {
      $_SESSION['validation_errors'] = $errors;
      Message::set('error', 'Vui lòng điền đầy đủ thông tin hợp lệ.');
      redirect('register');
    }

    if ($password !== $password_confirm) {
      Message::set('error', 'Mật khẩu xác nhận không khớp.');
      redirect('register');
    }

    $existingUser = $this->userModel->findByEmail($email);
    if ($existingUser) {
      Message::set('error', 'Email này đã được đăng ký.');
      redirect('register');
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $newUser = [
      'fullname' => $fullname,
      'email' => $email,
      'phone' => $phone,
      'password' => $hashedPassword,
    ];

    $userId = $this->userModel->create($newUser);

    if ($userId) {
      $user = $this->userModel->findById($userId);
      
      if (!$user) {
        Message::set('error', 'Tài khoản được tạo nhưng không thể lấy thông tin.');
        redirect('register');
      }

      $_SESSION['currentUser'] = [
        'id'       => $user['id'],
        'fullname' => $user['fullname'],
        'email'    => $user['email'],
        'roles'    => $user['roles'],
        'status'   => $user['status'],
        'avatar'   => $user['avatar'] ?? null,
      ];

      unset($_SESSION['old']);

      Message::set('success', 'Đăng ký thành công! Chào mừng ' . $user['fullname']);
      redirect('/');
    } else {
      Message::set('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
      redirect('register');
    }
  }

  public function logout()
  {
    unset($_SESSION['currentUser']);
    Message::set('success', 'Đã đăng xuất thành công.');
    redirect('login');
  }

  // Profile
  public function profile()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      redirect('login');
    }

    $user = $this->userModel->findById($userId);
    require_once './views/customer/profile.php';
  }

  public function updateProfile()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      redirect('login');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('profile');
    }

    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $errors = validate([
      'fullname' => $fullname,
      'phone' => $phone,
    ], [
      'fullname' => 'required|min:3',
      'phone' => 'required',
    ]);

    if (!empty($errors)) {
      Message::set('error', 'Vui lòng kiểm tra lại thông tin (Họ tên tối đa, SĐT không được để trống).');
      redirect('profile');
    }

    $data = [
      'fullname' => $fullname,
      'phone' => $phone,
      'address' => $address,
    ];

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
      $file = $_FILES['avatar'];
      $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
      $allowed = ['jpg', 'jpeg', 'png', 'gif'];
      if (!in_array(strtolower($ext), $allowed)) {
        Message::set('error', 'Ảnh đại diện sai định dạng.');
        redirect('profile');
      }
      $filename = time() . '_' . rand(1000, 9999) . '.' . $ext;
      $dest = './uploads/avatars/' . $filename;
      
      if (!is_dir('./uploads/avatars')) {
        mkdir('./uploads/avatars', 0777, true);
      }
      if (move_uploaded_file($file['tmp_name'], $dest)) {
        $data['avatar'] = '/uploads/avatars/' . $filename;
      }
    }

    if ($this->userModel->updateProfile($userId, $data)) {
      $user = $this->userModel->findById($userId);
      $_SESSION['currentUser']['fullname'] = $user['fullname'];
      $_SESSION['currentUser']['avatar'] = $user['avatar'];
      Message::set('success', 'Cập nhật thông tin cá nhân thành công.');
    } else {
      Message::set('error', 'Cập nhật thất bại. Vui lòng thử lại.');
    }

    redirect('profile');
  }

  // đổi mật khẩu customer
  public function updatePassword()
  {
    $userId = (int)($_SESSION['currentUser']['id'] ?? 0);
    if ($userId <= 0) {
      redirect('login');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('profile');
    }

    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $errors = validate([
      'old_password' => $oldPassword,
      'new_password' => $newPassword,
      'confirm_password' => $confirmPassword,
    ], [
      'old_password' => 'required',
      'new_password' => 'required|min:8',
      'confirm_password' => 'required|min:8',
    ]);

    if (!empty($errors)) {
      Message::set('error', 'Vui lòng nhập đầy đủ và chuẩn xác các trường mật khẩu.');
      redirect('profile');
    }

    if ($newPassword !== $confirmPassword) {
      Message::set('error', 'Mật khẩu mới không khớp xác nhận.');
      redirect('profile');
    }

    $user = $this->userModel->findById($userId);
    if (!$user || !password_verify($oldPassword, $user['password'])) {
      Message::set('error', 'Mật khẩu hiện tại không chính xác.');
      redirect('profile');
    }

    if ($this->userModel->changePassword($userId, $newPassword)) {
      Message::set('success', 'Đổi mật khẩu thành công.');
    } else {
      Message::set('error', 'Không thể đổi mật khẩu, vui lòng thử lại.');
    }

    redirect('profile');
  }
}
