<?php
class UserModel
{
  private $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  public function findByEmail($email)
  {
    $stmt = $this->conn->prepare("
      SELECT 
        user_id as id, 
        role_id as roles, 
        full_name as fullname, 
        email, 
        password, 
        phone, 
        address,
        avatar, 
        status, 
        created_at
      FROM users 
      WHERE email = :email 
      LIMIT 1
    ");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
  }

  public function findById($id)
  {
    $stmt = $this->conn->prepare("
      SELECT 
        user_id as id, 
        role_id as roles, 
        full_name as fullname, 
        email, 
        password, 
        phone, 
        address,
        avatar, 
        status, 
        created_at
      FROM users 
      WHERE user_id = :id 
      LIMIT 1
    ");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
  }

  public function create($data)
  {
    try {
      $stmt = $this->conn->prepare("
        INSERT INTO users (role_id, full_name, email, password, phone, address, status, avatar)
        VALUES (:role_id, :fullname, :email, :password, :phone, :address, :status, :avatar)
      ");
      $stmt->execute([
        'role_id'  => 2, // Always Customer for client registration
        'fullname' => $data['fullname'],
        'email'    => $data['email'],
        'password' => $data['password'],
        'phone'    => $data['phone'] ?? null,
        'address'  => null,
        'status'   => 1, // Active by default
        'avatar'   => null,
      ]);
      return $this->conn->lastInsertId();
    } catch (PDOException $e) {
      return false;
    }
  }
}
