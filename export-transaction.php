<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();

$currentdate = date('Y-m-d');

// Modify the SQL query to join transactions with users to fetch name and mobile
$sql_query = "SELECT transactions.user_id, users.name, users.mobile, transactions.type, transactions.datetime, transactions.amount 
              FROM `transactions`
              JOIN `users` ON transactions.user_id = users.id"; // Assuming user_id in transactions matches id in users

$db->sql($sql_query);
$developer_records = $db->getResult();

$filename = "AllTransactions-data" . date('Ymd') . ".xls";
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
