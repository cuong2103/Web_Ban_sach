<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Kết nối CSDL qua PDO
function connectDB()
{
    // Kết nối CSDL
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn;
    } catch (PDOException $e) {
        echo ("Connection failed: " . $e->getMessage());
    }
}

function uploadFile($file, $folderSave, &$error = null)
{
    $error = null;
    $fileUpload = $file;

    if (($fileUpload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $error = 'File upload thất bại (mã lỗi: ' . (int) ($fileUpload['error'] ?? -1) . ').';
        return null;
    }

    $tmpFile = $fileUpload['tmp_name'] ?? '';
    if (!$tmpFile || !is_uploaded_file($tmpFile)) {
        $error = 'Không tìm thấy file tạm hoặc file upload không hợp lệ.';
        return null;
    }

    $originalName = (string) ($fileUpload['name'] ?? '');
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if ($extension === '') {
        $extension = 'bin';
    }

    $normalizedFolder = '/' . trim((string) $folderSave, '/') . '/';
    $absoluteFolder = rtrim(PATH_ROOT, '\\/') . str_replace('/', DIRECTORY_SEPARATOR, $normalizedFolder);

    if (!is_dir($absoluteFolder) && !mkdir($absoluteFolder, 0755, true)) {
        $error = 'Không thể tạo thư mục lưu ảnh.';
        return null;
    }

    $baseName = pathinfo($originalName, PATHINFO_FILENAME);
    $safeBaseName = preg_replace('/[^A-Za-z0-9_-]/', '-', $baseName);
    $safeBaseName = trim((string) $safeBaseName, '-');
    if ($safeBaseName === '') {
        $safeBaseName = 'image';
    }

    try {
        $random = bin2hex(random_bytes(4));
    } catch (Exception $e) {
        $random = (string) rand(10000, 99999);
    }

    $fileName = date('YmdHis') . '-' . $random . '-' . $safeBaseName . '.' . $extension;
    $relativePath = $normalizedFolder . $fileName;
    $absolutePath = rtrim(PATH_ROOT, '\\/') . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if (move_uploaded_file($tmpFile, $absolutePath)) {
        return $relativePath;
    }

    $error = 'Không thể lưu file ảnh vào thư mục uploads.';
    return null;
}

// Hàm xoá file
function deleteFile($path)
{
    $fullPath = PATH_ROOT . ltrim($path, '/');
    if (file_exists($fullPath) && is_file($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}


// Hàm debug
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

//Xóa session sau khi load trang
function deleteSessionError()
{
    if (isset($_SESSION['flash'])) {
        unset($_SESSION['flash']);
        unset($_SESSION['error']);
    }
}

// Hàm check login 
function checkLogin()
{
    if (!isset($_SESSION['currentUser']["roles"])) {
        $act = $_GET['act'] ?? '/';
        // Nếu đang cố vào link admin thì redirect ra đăng nhập admin, ngược lại đẩy ra đăng nhập khách
        if (str_starts_with($act, 'admin-')) {
            header("Location: " . BASE_URL . '?act=admin-login');
        } else {
            header("Location: " . BASE_URL . '?act=login');
        }
        exit();
    }
}

function requireAdmin()
{
    // Kiểm tra đang login
    if (!isset($_SESSION['currentUser'])) {
        redirect("admin-login");
    }

    // Kiểm tra trạng thái
    if ($_SESSION['currentUser']['status'] != 1) {
        Message::set("error", "Tài khoản đã bị khóa!");
        unset($_SESSION['currentUser']);
        redirect("admin-login");
    }

    if (($_SESSION['currentUser']['roles'] ?? '') != 1) {
        Message::set("error", "403 - Chỉ Admin được phép truy cập!");
        redirect("403");
    }
}

function validate($data, $rules)
{
    $errors = [];

    foreach ($rules as $field => $ruleString) {
        $rulesArray = explode('|', $ruleString);

        foreach ($rulesArray as $rule) {
            if ($rule === 'required') {
                if (!isset($data[$field])) {
                    $errors[$field][] = "Trường này bắt buộc phải nhập.";
                } else {
                    // nếu là mảng
                    if (is_array($data[$field])) {
                        foreach ($data[$field] as $i => $value) {
                            if (trim((string) $value) === '') {
                                $errors[$field][$i][] = "Trường này bắt buộc phải nhập.";
                            }
                        }
                    } else {
                        if (trim($data[$field]) === '') {
                            $errors[$field][] = "Trường này bắt buộc phải nhập.";
                        }
                    }
                }
            } elseif ($rule === 'email') {
                if (isset($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "Trường này phải là email hợp lệ.";
                }
            } elseif (strpos($rule, 'min:') === 0) {
                $min = (int) explode(':', $rule)[1];
                if (isset($data[$field]) && strlen($data[$field]) < $min) {
                    $errors[$field][] = "Trường này phải có ít nhất $min ký tự.";
                }
            } elseif (strpos($rule, 'max:') === 0) {
                $max = (int) explode(':', $rule)[1];
                if (isset($data[$field]) && strlen($data[$field]) > $max) {
                    $errors[$field][] = "Trường này không được vượt quá $max ký tự.";
                }
            } elseif ($rule === 'numeric') {
                if (isset($data[$field]) && !is_numeric($data[$field])) {
                    $errors[$field][] = "Trường này phải là số.";
                }
            } elseif ($rule === 'array') {
                if (!isset($data[$field]) || !is_array($data[$field])) {
                    $errors[$field][] = "Trường này phải là mảng.";
                }
            } elseif (substr($rule, 0, 2) === '*:') {
                $subRule = substr($rule, 2); // lấy rule bên trong
                if (isset($data[$field]) && is_array($data[$field])) {
                    foreach ($data[$field] as $i => $value) {
                        if ($subRule === 'required' && trim($value) === '') {
                            $errors[$field][$i][] = "Trường này bắt buộc phải nhập.";
                        }
                        if ($subRule === 'numeric' && !is_numeric($value)) {
                            $errors[$field][$i][] = "Phần tử $i của này phải là số.";
                        }
                    }
                }
            } elseif ($rule === 'phone') {
                if (isset($data[$field]) && !preg_match('/^(0|\+84)[0-9]{9,10}$/', $data[$field])) {
                    $errors[$field][] = "Trường này phải là số điện thoại hợp lệ.";
                }
            } elseif ($rule === 'time') {
                if (isset($data[$field]) && !preg_match('/^\d{1,2}:\d{2}$/', $data[$field])) {
                    $errors[$field][] = "Trường $field phải có định dạng giờ HH:MM.";
                }
            }
        }
    }

    return $errors;
}

// Hàm lấy giá trị cũ từ session (dùng khi validation thất bại)
function old($key, $default = '')
{
    if (isset($_SESSION['old'][$key])) {
        return htmlspecialchars($_SESSION['old'][$key]);
    }
    return $default;
}

function redirect($act)
{
    header("Location: " . BASE_URL . "?act=" . $act);
    exit();
}

function timeAgo($datetime)
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'Vừa xong';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' phút trước';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' giờ trước';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' ngày trước';
    } else {
        return date('d/m/Y H:i', $timestamp);
    }
}