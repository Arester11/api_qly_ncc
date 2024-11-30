<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Kiểm tra phương thức request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(array("message" => "Phương thức không được cho phép", "success" => false));
    exit();
}

include_once('../../config/db.php');
include_once('../../model/suppliers.php');

$db = new db();
$connect = $db->connect();
$supplier = new Suppliers($connect);

// Lấy ID từ URL parameter hoặc request body
$id = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    // Parse DELETE request body
    parse_str(file_get_contents("php://input"), $delete_vars);
    $id = isset($delete_vars['id']) ? $delete_vars['id'] : null;
}

if ($id) {
    $id = trim($id);
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(array("message" => "ID nhà cung cấp phải là số.", 'success' => false));
        exit();
    }
    
    $supplier->idNCC = $id;
    
    // Kiểm tra sự tồn tại của nhà cung cấp
    $check = $supplier->read_single();
    if (!$check || $check->rowCount() == 0) {
        http_response_code(404);
        echo json_encode(array("message" => "Không tìm thấy nhà cung cấp với ID này.", 'success' => false));
        exit();
    }

    if ($supplier->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Nhà cung cấp đã được xóa thành công.", 'success' => true));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Không thể xóa nhà cung cấp.", 'success' => false));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Không thể xóa nhà cung cấp. Thiếu ID.", 'success' => false));
}
?> 