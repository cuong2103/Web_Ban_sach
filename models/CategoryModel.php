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
    public function getAll($search = '', $date = '', $limit = 10, $offset = 0)
    {
        $query = "
      SELECT 
        category_id as id,
        name,
        slug,
        description,
        status,
        created_at,
        updated_at
      FROM categories
      WHERE 1=1
    ";

        if (!empty($search)) {
            $query .= " AND (name LIKE ? OR slug LIKE ?)";
        }

        if (!empty($date)) {
            $query .= " AND DATE(created_at) = ?";
        }

        $query .= " ORDER BY category_id DESC LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($query);

        $paramIndex = 1;
        if (!empty($search)) {
            $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
        }

        if (!empty($date)) {
            $stmt->bindValue($paramIndex++, $date, PDO::PARAM_STR);
        }

        $stmt->bindValue($paramIndex++, (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex++, (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countAll($search = '', $date = '')
    {
        $query = "
      SELECT COUNT(*) as total
      FROM categories
      WHERE 1=1
    ";

        if (!empty($search)) {
            $query .= " AND (name LIKE ? OR slug LIKE ?)";
        }

        if (!empty($date)) {
            $query .= " AND DATE(created_at) = ?";
        }

        $stmt = $this->conn->prepare($query);

        $paramIndex = 1;
        if (!empty($search)) {
            $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue($paramIndex++, '%' . $search . '%', PDO::PARAM_STR);
        }

        if (!empty($date)) {
            $stmt->bindValue($paramIndex++, $date, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetch()['total'] ?? 0;
    }

    public function findBySlug($slug)
    {
        $stmt = $this->conn->prepare("
      SELECT 
        category_id as id,
        name,
        slug,
        description,
        status,
        created_at,
        updated_at
      FROM categories
      WHERE slug = :slug
      LIMIT 1
    ");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }
}
?>