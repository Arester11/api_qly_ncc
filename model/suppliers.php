<?php
class Suppliers
{
  private $conn;

  public $idNCC;
  public $tenNCC;
  public $diachi;
  public $email;
  public $dienthoai;
  public $trangthai;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Đọc danh sách nhà cung cấp
  public function read()
  {
    $query = "SELECT * FROM nhacungcap ORDER BY idNCC ASC";
    $stmt = $this->conn->prepare($query);
    if ($stmt->execute()) {
      return $stmt;
    } 
    return false;
  }

  // Đọc một nhà cung cấp
  public function read_single() {
    $query = "SELECT * FROM nhacungcap WHERE idNCC = :idNCC LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':idNCC', $this->idNCC);

    if ($stmt->execute()) {
      return $stmt;
    } 
    return false;
  }

  // Tạo nhà cung cấp mới
  public function create() {
    $query = "INSERT INTO nhacungcap SET
        tenNCC = :tenNCC,
        diachi = :diachi,
        email = :email,
        dienthoai = :dienthoai,
        trangthai = 1";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':tenNCC', $this->tenNCC);
    $stmt->bindParam(':diachi', $this->diachi);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':dienthoai', $this->dienthoai);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // Cập nhật thông tin nhà cung cấp
  public function update()
  {
    $query = "UPDATE nhacungcap SET
              tenNCC = :tenNCC,
              diachi = :diachi, 
              email = :email,
              dienthoai = :dienthoai
          WHERE idNCC = :idNCC";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':idNCC', $this->idNCC);
    $stmt->bindParam(':tenNCC', $this->tenNCC);
    $stmt->bindParam(':diachi', $this->diachi);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':dienthoai', $this->dienthoai);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // Xóa nhà cung cấp (cập nhật trạng thái)
  public function delete()
  {
    $query = "DELETE FROM nhacungcap WHERE idNCC = :idNCC";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':idNCC', $this->idNCC);
    return $stmt->execute();
  }
} 