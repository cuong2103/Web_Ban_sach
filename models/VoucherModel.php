<?php
class VoucherModel
{
  private $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  public function getAll()
  {
    $stmt = $this->conn->prepare("
      SELECT 
        voucher_id, code, discount_type, discount_value, max_discount, min_order_value, start_date, end_date, status
      FROM vouchers
      ORDER BY voucher_id DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function findById($id)
  {
    $stmt = $this->conn->prepare("
      SELECT 
        voucher_id, code, discount_type, discount_value, max_discount, min_order_value, start_date, end_date, status
      FROM vouchers
      WHERE voucher_id = :id
      LIMIT 1
    ");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
  }

  public function create($data)
  {
    try {
      $stmt = $this->conn->prepare("
        INSERT INTO vouchers (code, discount_type, discount_value, max_discount, min_order_value, start_date, end_date, status)
        VALUES (:code, :discount_type, :discount_value, :max_discount, :min_order_value, :start_date, :end_date, :status)
      ");
      $stmt->execute([
        'code' => $data['code'],
        'discount_type' => $data['discount_type'],
        'discount_value' => $data['discount_value'],
        'max_discount' => $data['max_discount'] !== '' ? $data['max_discount'] : null,
        'min_order_value' => $data['min_order_value'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'] !== '' ? $data['end_date'] : null,
        'status' => $data['status']
      ]);
      return $this->conn->lastInsertId();
    } catch (PDOException $e) {
      return false;
    }
  }

  public function update($id, $data)
  {
    try {
      $stmt = $this->conn->prepare("
        UPDATE vouchers
        SET code = :code,
            discount_type = :discount_type,
            discount_value = :discount_value,
            max_discount = :max_discount,
            min_order_value = :min_order_value,
            start_date = :start_date,
            end_date = :end_date,
            status = :status
        WHERE voucher_id = :id
      ");
      return $stmt->execute([
        'code' => $data['code'],
        'discount_type' => $data['discount_type'],
        'discount_value' => $data['discount_value'],
        'max_discount' => $data['max_discount'] !== '' ? $data['max_discount'] : null,
        'min_order_value' => $data['min_order_value'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'] !== '' ? $data['end_date'] : null,
        'status' => $data['status'],
        'id' => $id
      ]);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function delete($id)
  {
    try {
      $stmt = $this->conn->prepare("DELETE FROM vouchers WHERE voucher_id = :id");
      return $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
      return false;
    }
  }
}
