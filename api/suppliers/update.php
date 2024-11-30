<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once('../../config/db.php');
include_once('../../model/suppliers.php');

$db = new db();
$connect = $db->connect();
$supplier = new Suppliers($connect);
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->idNCC) && !empty($data->tenNCC) && !empty($data->diachi) && !empty($data->email) && !empty($data->dienthoai)) {
    $supplier->idNCC = $data->idNCC;
    
    // Kiểm tra sự tồn tại của nhà cung cấp
    $check = $supplier->read_single();
    if (!$check || $check->rowCount() == 0) {
        http_response_code(404);
        echo json_encode(array("message" => "Không tìm thấy nhà cung cấp với ID này.", 'success' => false));
        exit();
    }

    $supplier->tenNCC = $data->tenNCC;
    $supplier->diachi = $data->diachi;
    $supplier->email = $data->email;
    $supplier->dienthoai = $data->dienthoai;

    if ($supplier->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Cập nhật nhà cung cấp thành công.", 'success' => true));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Không thể cập nhật nhà cung cấp.", 'success' => false));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Không thể cập nhật nhà cung cấp. Dữ liệu không đầy đủ.", 'success' => false));
}
?>