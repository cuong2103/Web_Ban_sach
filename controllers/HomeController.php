<?php
class HomeController{
    public function home(){
        $title = "Trang chủ";
        $books = (new BookModel())->getAll();
    }
}