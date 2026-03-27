<?php
class BookModel
{
  private $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }
}