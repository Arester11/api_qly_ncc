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

  // Thêm phương thức mới vào class Suppliers
  public function checkEmailExists($email) {
    $query = "SELECT COUNT(*) as count FROM nhacungcap WHERE email = :email";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    
    if ($stmt->execute()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }
    return false;
  }

  public function validateEmail($email) {
    // Kiểm tra định dạng email bằng filter_var
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
  }
} 