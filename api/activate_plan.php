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
include_once('verify-token.php');
$fn = new functions;


$date = date('Y-m-d');

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['plan_id'])) {
    $response['success'] = false;
    $response['message'] = "Plan Id is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$plan_id = $db->escapeString($_POST['plan_id']);

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}
$recharge = $user[0]['recharge'];

$sql = "SELECT * FROM plan WHERE id = $plan_id ";
$db->sql($sql);
$plan = $db->getResult();

if (empty($plan)) {
    $response['success'] = false;
    $response['message'] = "Plan not found";
    print_r(json_encode($response));
    return false;
}

$price = $plan[0]['price'];
$datetime = date('Y-m-d H:i:s');


    $sql_check = "SELECT * FROM user_plan WHERE user_id = $user_id AND plan_id = $plan_id";
    $db->sql($sql_check);
    $res_check_user = $db->getResult();

    if (!empty($res_check_user)) {
        $response['success'] = false;
        $response['message'] = "You have already started this plan";
        print_r(json_encode($response));
        return false;
    }
    if ($recharge >= $price) {

        $sql = "UPDATE users SET recharge = recharge - $price, total_assets = total_assets + $price WHERE id = $user_id";
        $db->sql($sql);

    $sql_insert_user_plan = "INSERT INTO user_plan (user_id,plan_id,joined_date) VALUES ('$user_id','$plan_id','$date')";
    $db->sql($sql_insert_user_plan);

    $sql_insert_transaction = "INSERT INTO transactions (user_id, amount, datetime, type) VALUES ('$user_id', '$price', '$datetime', 'start_production')";
    $db->sql($sql_insert_transaction);

    $sql_user = "SELECT * FROM users WHERE id = $user_id";
    $db->sql($sql_user);
    $res_user = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Plan started successfully";
    $response['data'] = $res_user;
    }
    else {
        $response['success'] = false;
        $response['message'] = "Insufficient balance to start this plan";
    }
print_r(json_encode($response));
?>