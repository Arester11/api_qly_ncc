<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/suppliers.php');

try {
  $db = new db();
  $connect = $db->connect();
  $supplier = new Suppliers($connect);

  if (isset($_GET['id'])) {
    $supplier->idNCC = $_GET['id'];
    $read = $supplier->read_single();

    if ($read && $read->rowCount() > 0) {
      $supplier_array = [];
      $supplier_array['data'] = [];

      $row = $read->fetch(PDO::FETCH_ASSOC);
      $supplier_item = array(
        'idNCC' => $row['idNCC'],
        'tenNCC' => $row['tenNCC'],
        'diachi' => $row['diachi'],
        'email' => $row['email'],
        'dienthoai' => $row['dienthoai'],
        'trangthai' => $row['trangthai']
      );

      array_push($supplier_array['data'], $supplier_item);

      http_response_code(200);
      echo json_encode($supplier_array);
    } else {
      http_response_code(404);
      echo json_encode(array('message' => 'Supplier not found.', 'success' => false));
    }
  } else {
    http_response_code(400);
    echo json_encode(array('message' => 'Supplier ID is required.', 'success' => false));
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