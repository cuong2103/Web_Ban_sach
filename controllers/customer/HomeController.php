<?php
class HomeController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function home()
    {
        $bookModel = new BookModel();
        $categories = $this->categoryModel->getActive();

        // Fetch data for home page sections
        $flashSaleModel = new FlashSaleModel();
        $activeFlashSale = $flashSaleModel->getActiveFlashSale();
        $saleBooks = [];
        if ($activeFlashSale) {
            $saleBooks = $flashSaleModel->getItems($activeFlashSale['flash_sale_id']);
            // Giới hạn hiển thị 4 sản phẩm
            $saleBooks = array_slice($saleBooks, 0, 4);
        }

        $newBooks  = $bookModel->getNewBooks(4);
        $hotBooks  = $bookModel->getBestsellerWithSoldCount(4);

        require_once './views/customer/home.php';
    }

    public function about()
    {
        require_once './views/customer/about.php';
    }

    public function contact()
    {
        require_once './views/customer/contact.php';
    }
}
