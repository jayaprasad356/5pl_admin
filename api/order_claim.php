<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');

include_once('../includes/crud.php');

$db = new Database();
$db->connect();
include_once('../includes/custom-functions.php');
include_once('../includes/functions.php');
$fn = new functions;
$datetime = date('Y-m-d H:i:s');

if (empty($_POST['order_id'])) {
    $response['success'] = false;
    $response['message'] = "Order Id is Empty";
    echo json_encode($response);
    return;
}
if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    echo json_encode($response);
    return;
}

$order_id = $db->escapeString($_POST['order_id']);
$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    echo json_encode($response);
    return;
}

$sql = "SELECT claim,amount FROM payments WHERE order_id = '$order_id'";
$db->sql($sql);
$payments = $db->getResult();

if (empty($payments)) {
    $response['success'] = false;
    $response['message'] = "Invalid Order Id";
    echo json_encode($response);
    return;
}

$amount = $payments[0]['amount'];
$claim = $payments[0]['claim'];

if ($claim == 1) {
    $response['success'] = false;
    $response['message'] = "Already Claimed";
    echo json_encode($response);
    return;
}

$sql = "UPDATE payments SET claim = 1 WHERE order_id = '$order_id'";
$db->sql($sql);

$sql = "UPDATE users SET recharge = recharge + '$amount', total_recharge = total_recharge + '$amount' WHERE id = '$user_id'";
$db->sql($sql);

$sql = "INSERT INTO transactions (user_id, amount, datetime, type) VALUES ('$user_id', '$amount', '$datetime', 'recharge')";
$db->sql($sql);

$response['success'] = true;
$response['message'] = "Payments Completed Successfully";
echo json_encode($response);
?>
