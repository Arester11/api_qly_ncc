<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/db.php');
include_once('../../model/suppliers.php');

$db = new db();
$connect = $db->connect();
$supplier = new Suppliers($connect);
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->tenNCC) &&
    !empty($data->diachi) &&
    !empty($data->email) &&
    !empty($data->dienthoai)
) {
    $supplier->tenNCC = $data->tenNCC;
    $supplier->diachi = $data->diachi;
    $supplier->email = $data->email;
    $supplier->dienthoai = $data->dienthoai;

    if ($supplier->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Supplier was created.", 'success' => true));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create supplier.", 'success' => false));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create supplier. Data is incomplete.", 'success' => false));
}
?> 