<?php

class AdminCategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function list()
    {
        requireAdmin();

        $search = trim($_GET['search'] ?? '');
        $date = trim($_GET['date'] ?? '');
        $page = (int) ($_GET['page'] ?? 1);
        $page = $page < 1 ? 1 : $page;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $categories = $this->categoryModel->getAll($search, $date, $limit, $offset);
        $total = $this->categoryModel->countAll($search, $date);
        $totalPages = ceil($total / $limit);

        require_once './views/admin/categories/list.php';
    }

    public function create()
    {
        requireAdmin();

        $old = $_SESSION['old'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['old'], $_SESSION['errors']);

        require_once './views/admin/categories/create.php';
    }

    public function store()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'slug' => $_POST['slug'] ?? '',
                'description' => $_POST['description'] ?? '',
                'status' => $_POST['status'] ?? 1,
            ];

            $_SESSION['old'] = $data;

            $errors = validate($data, [
                'name' => 'required|min:3|max:150',
                'slug' => 'required|min:3|max:150',
            ]);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                redirect('admin-categories-create');
            }

            // Check if slug already exists
            $existingCategory = $this->categoryModel->findBySlug($data['slug']);
            if ($existingCategory) {
                $_SESSION['errors'] = ['slug' => 'Slug này đã được sử dụng'];
                $_SESSION['old'] = $data;
                redirect('admin-categories-create');
            }

            $stmt = connectDB()->prepare("
                INSERT INTO categories (name, slug, description, status)
                VALUES (:name, :slug, :description, :status)
            ");

            if ($stmt->execute($data)) {
                Message::set('success', 'Thêm danh mục thành công');
                redirect('admin-categories');
            } else {
                Message::set('error', 'Có lỗi khi thêm danh mục');
                redirect('admin-categories-create');
            }
        }
    }

    public function edit()
    {
        requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            Message::set('error', 'Danh mục không tồn tại');
            redirect('admin-categories');
        }

        $category = $this->categoryModel->findById($id);
        if (!$category) {
            Message::set('error', 'Danh mục không tồn tại');
            redirect('admin-categories');
        }

        $old = $_SESSION['old'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['old'], $_SESSION['errors']);

        require_once './views/admin/categories/edit.php';
    }

    public function update()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id <= 0) {
                Message::set('error', 'Danh mục không tồn tại');
                redirect('admin-categories');
            }

            $category = $this->categoryModel->findById($id);
            if (!$category) {
                Message::set('error', 'Danh mục không tồn tại');
                redirect('admin-categories');
            }

            $data = [
                'name' => $_POST['name'] ?? '',
                'slug' => $_POST['slug'] ?? '',
                'description' => $_POST['description'] ?? '',
                'status' => $_POST['status'] ?? 1,
            ];

            $_SESSION['old'] = $data;

            $errors = validate($data, [
                'name' => 'required|min:3|max:150',
                'slug' => 'required|min:3|max:150',
            ]);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                redirect('admin-categories-edit&id=' . $id);
            }

            // Check if slug already exists (except current)
            $existingCategory = $this->categoryModel->findBySlug($data['slug']);
            if ($existingCategory && $existingCategory['id'] != $id) {
                $_SESSION['errors'] = ['slug' => 'Slug này đã được sử dụng'];
                $_SESSION['old'] = $data;
                redirect('admin-categories-edit&id=' . $id);
            }

            $stmt = connectDB()->prepare("
                UPDATE categories 
                SET name = :name, 
                    slug = :slug, 
                    description = :description, 
                    status = :status
                WHERE category_id = :id
            ");

            $data['id'] = $id;

            if ($stmt->execute($data)) {
                Message::set('success', 'Cập nhật danh mục thành công');
                redirect('admin-categories');
            } else {
                Message::set('error', 'Có lỗi khi cập nhật danh mục');
                redirect('admin-categories-edit&id=' . $id);
            }
        }
    }

    public function detail()
    {
        requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            Message::set('error', 'Danh mục không tồn tại');
            redirect('admin-categories');
        }

        $category = $this->categoryModel->findById($id);
        if (!$category) {
            Message::set('error', 'Danh mục không tồn tại');
            redirect('admin-categories');
        }

        require_once './views/admin/categories/detail.php';
    }

    public function delete()
    {
        requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            Message::set('error', 'Danh mục không tồn tại');
            redirect('admin-categories');
        }

        $category = $this->categoryModel->findById($id);
        if (!$category) {
            Message::set('error', 'Danh mục không tồn tại');
            redirect('admin-categories');
        }

        $stmt = connectDB()->prepare("DELETE FROM categories WHERE category_id = :id");

        if ($stmt->execute(['id' => $id])) {
            Message::set('success', 'Xóa danh mục thành công');
        } else {
            Message::set('error', 'Có lỗi khi xóa danh mục');
        }

        redirect('admin-categories');
    }
}
?>