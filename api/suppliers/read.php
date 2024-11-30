<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(array("message" => "Phương thức không được cho phép", "success" => false));
    exit();
}

include_once('../../config/db.php');
include_once('../../model/suppliers.php');

try {
  $db = new db();
  $connect = $db->connect();
  $suppliers = new Suppliers($connect);
  $read = $suppliers->read();
  $num = $read->rowCount();

  if ($num > 0) {
    $supplier_array = [];
    $supplier_array['data'] = []; 

    while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      $supplier_item = array(
        'idNCC' => $idNCC,
        'tenNCC' => $tenNCC,
        'diachi' => $diachi,
        'email' => $email,
        'dienthoai' => $dienthoai,
        'trangthai' => $trangthai
      );

      array_push($supplier_array['data'], $supplier_item);
    }
    http_response_code(200);
    echo json_encode($supplier_array);
  } else {
    http_response_code(404);
    echo json_encode(array('message' => 'Không tìm thấy nhà cung cấp nào.', 'success' => false));
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(array(
    'message' => 'Lỗi máy chủ nội bộ',
    'error' => $e->getMessage(),
    'success' => false
  ));
}
?> 