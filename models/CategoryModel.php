<?php
class CategoryModel
{
    private $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function getActive()
    {
        $stmt = $this->conn->prepare("
      SELECT 
        category_id as id,
        name,
        slug
      FROM categories
      WHERE status = 1
      ORDER BY name ASC
    ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>