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
    $supplier = new Suppliers($connect);

    // Kiểm tra ID có tồn tại và không rỗng
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array('message' => 'Thiếu tham số ID nhà cung cấp.', 'success' => false));
        exit();
    }

    // Kiểm tra ID sau khi đã trim
    $id = trim($_GET['id']);
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID nhà cung cấp không được để trống.', 'success' => false));
        exit();
    }

    // Kiểm tra ID có phải là số
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(array('message' => 'ID nhà cung cấp phải là số.', 'success' => false));
        exit();
    }

    $supplier->idNCC = $id;
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
        echo json_encode(array('message' => 'Không tìm thấy nhà cung cấp.', 'success' => false));
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