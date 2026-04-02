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
}
?>