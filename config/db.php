<?php
class db {
  private $servername = "localhost";
  private $username = "root"; 
  private $password = "";
  private $dbname = "bansach";
  private $conn;

  public function connect() {
    try {
      $this->conn = new PDO(    
        "mysql:host=" . $this->servername . ";dbname=" . $this->dbname,
        $this->username,
        $this->password
      );
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo "Lỗi kết nối: " . $e->getMessage();
    }
    return $this->conn;
  }
}
?>