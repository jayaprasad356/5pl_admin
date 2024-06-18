<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();

$currentdate = date('Y-m-d');
$sql_query = "SELECT up.id, up.user_id, u.name AS user_name, u.mobile, up.plan_id, p.name AS plan_name, p.price, p.daily_codes, p.daily_earnings, p.per_code_cost, up.income, up.joined_date, up.claim
              FROM `user_plan` up
              JOIN `users` u ON up.user_id = u.id
              JOIN `plan` p ON up.plan_id = p.id
              ORDER BY up.id"; // Join user_plan with users and plans on user_id and plan_id respectively, and order by user_plan id
$db->sql($sql_query);
$developer_records = $db->getResult();

$filename = "AllUserPlan-data" . date('Ymd') . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

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
