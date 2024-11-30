<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(array("message" => "Phương thức không được cho phép", "success" => false));
    exit();
}

include_once('../../config/db.php');
include_once('../../model/suppliers.php');

$db = new db();
$connect = $db->connect();
$supplier = new Suppliers($connect);
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra từng trường dữ liệu
if (!isset($data->idNCC) || empty(trim($data->idNCC))) {
    http_response_code(400);
    echo json_encode(array("message" => "ID nhà cung cấp không được để trống.", 'success' => false));
    exit();
}

if (!isset($data->tenNCC) || empty(trim($data->tenNCC))) {
    http_response_code(400);
    echo json_encode(array("message" => "Tên nhà cung cấp không được để trống.", 'success' => false));
    exit();
}

if (!isset($data->diachi) || empty(trim($data->diachi))) {
    http_response_code(400);
    echo json_encode(array("message" => "Địa chỉ không được để trống.", 'success' => false));
    exit();
}

if (!isset($data->email) || empty(trim($data->email))) {
    http_response_code(400);
    echo json_encode(array("message" => "Email không được để trống.", 'success' => false));
    exit();
}

if (!isset($data->dienthoai) || empty(trim($data->dienthoai))) {
    http_response_code(400);
    echo json_encode(array("message" => "Số điện thoại không được để trống.", 'success' => false));
    exit();
}

if (!$supplier->validatePhone($data->dienthoai)) {
    http_response_code(400);
    echo json_encode(array("message" => "Số điện thoại không hợp lệ. Vui lòng nhập 10-11 số.", 'success' => false));
    exit();
}

// Kiểm tra định dạng email
if (!$supplier->validateEmail($data->email)) {
    http_response_code(400);
    echo json_encode(array("message" => "Định dạng email không hợp lệ.", 'success' => false));
    exit();
}

$supplier->idNCC = trim($data->idNCC);

// Kiểm tra sự tồn tại của nhà cung cấp
$check = $supplier->read_single();
if (!$check || $check->rowCount() == 0) {
    http_response_code(404);
    echo json_encode(array("message" => "Không tìm thấy nhà cung cấp với ID này.", 'success' => false));
    exit();
}

// Kiểm tra email trùng khi cập nhật
$currentSupplier = $check->fetch(PDO::FETCH_ASSOC);
if ($currentSupplier['email'] !== $data->email && $supplier->checkEmailExists($data->email)) {
    http_response_code(400);
    echo json_encode(array("message" => "Email này đã được sử dụng bởi nhà cung cấp khác.", 'success' => false));
    exit();
}

$supplier->tenNCC = trim($data->tenNCC);
$supplier->diachi = trim($data->diachi);
$supplier->email = trim($data->email);
$supplier->dienthoai = trim($data->dienthoai);

if ($supplier->update()) {
    http_response_code(200);
    echo json_encode(array("message" => "Cập nhật nhà cung cấp thành công.", 'success' => true));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Không thể cập nhật nhà cung cấp.", 'success' => false));
}
?>