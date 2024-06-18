<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();

$currentdate = date('Y-m-d');
$sql_query = "SELECT name, email,age,mobile,state,city,referred_by,refer_code,account_num,holder_name,bank,branch,ifsc,withdrawal_status,recharge,total_recharge,total_income,today_income,device_id,total_withdrawal,team_income,earning_wallet,bonus_wallet,balance,registered_datetime,blocked  FROM `users`"; // Fetch only name and email
$db->sql($sql_query);
$developer_records = $db->getResult();

$filename = "AllUsers-data" . date('Ymd') . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
$show_column = false;

if (!empty($developer_records)) {
    foreach ($developer_records as $record) {
        if (!$show_column) {
            // display field/column names in the first row
            echo implode("\t", array_keys($record)) . "\n";
            $show_column = true;
        }
        echo implode("\t", array_values($record)) . "\n";
    }
}

exit;
?>
