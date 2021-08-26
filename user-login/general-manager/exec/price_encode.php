<?php
date_default_timezone_set("Asia/Singapore");
session_start();
include('../config.php');

$client_id = $_POST['client_id'];
$date_effective = $_POST['date_effective'];
$date = date('Y/m/d');
$action = $_POST['action'];
$status = '1';
if($action == 'disapproved'){
    $status = '2';
}
echo $status.'-'.$date.'-'.$date_effective.'-'.$client_id;
mysql_query("UPDATE client_price SET status='$status', date_action='$date' WHERE date_effective='$date_effective' and client_id='$client_id'") or die(mysql_error());