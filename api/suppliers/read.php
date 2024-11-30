<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

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
    echo json_encode(array('message' => 'No suppliers found.', 'success' => false));
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(array(
    'message' => 'Internal Server Error',
    'error' => $e->getMessage(),
    'success' => false
  ));
}
?> 