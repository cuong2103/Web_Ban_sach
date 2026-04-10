<?php
class BookController
{
    private $bookModel;
    public function __construct()
    {
        $this->bookModel = new BookModel();
    }
    public function list()
    {
        $search = trim($_GET['search'] ?? '');
        $category = trim($_GET['category'] ?? '');

        // Get price range first
        $priceRange = $this->bookModel->getPriceRange();

        $minPrice = (int) ($_GET['min_price'] ?? $priceRange['min']);
        $maxPrice = (int) ($_GET['max_price'] ?? $priceRange['max']);
        $page = (int) ($_GET['page'] ?? 1);
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $books = $this->bookModel->getAll($search, $category, $minPrice, $maxPrice, $limit, $offset);
        $total = $this->bookModel->countAll($search, $category, $minPrice, $maxPrice);
        $totalPages = ceil($total / $limit);
        $categories = $this->bookModel->getCategories();

        require_once './views/customer/books.php';
    }
    public function detail()
    {
        $id = (int) ($_GET['id'] ?? 0);
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=books');
            exit;
        }

        $book = $this->bookModel->getById($id);
        if (!$book) {
            header('Location: ' . BASE_URL . '?act=books');
            exit;
        }

        $images = $this->bookModel->getBookImages($id);

        // Lấy sách cùng danh mục (loại trừ sách hiện tại)
        $relatedBooks = [];
        if (!empty($book['slug'])) {
            $categoryBooks = $this->bookModel->getBooksByCategory($book['slug'], 6); // Lấy 6 để phòng trường hợp trừ đi 1 cuốn hiện tại
            foreach ($categoryBooks as $cb) {
                if ($cb['id'] != $id) {
                    $relatedBooks[] = $cb;
                }
            }
            $relatedBooks = array_slice($relatedBooks, 0, 5); // Hiển thị 5 cuốn
        }

        $categories = $this->bookModel->getCategories();

        require_once './views/customer/book_detail.php';
    }
}
?>