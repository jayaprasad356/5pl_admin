<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');
$db = new Database();
$db->connect();


if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['order_id'])) {
    $response['success'] = false;
    $response['message'] = "Order Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['product_id'])) {
    $response['success'] = false;
    $response['message'] = "Product ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['amount'])) {
    $response['success'] = false;
    $response['message'] = "Amount is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['datetime'])) {
    $response['success'] = false;
    $response['message'] = "Datetime is Empty";
    print_r(json_encode($response));
    return false;
}

$datetime = $db->escapeString($_POST['datetime']);
$order_id = $db->escapeString($_POST['order_id']);
$amount = $db->escapeString($_POST['amount']);
$mobile = $db->escapeString($_POST['mobile']);
$product_id = $db->escapeString($_POST['product_id']);


$product_ids = json_decode($_POST['product_id'], true);

if (in_array(31904496, $product_ids)) {

    $url = 'https://admin.aidiapp.in/api/cp.php';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);

    $data = [
        'datetime' => $datetime,
        'order_id' => $order_id,
        'amount' => $amount,
        'mobile' => $mobile,
        'product_id' => $product_id
    ];
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
}

