<?php
date_default_timezone_set("Asia/Singapore");
	include('../config.php');

$ea_id = $_POST['ea_id'];
$action = $_POST['action'];
$date_time = date('Y-m-d H:i:s');

mysql_query("UPDATE employee_advances SET status='$action', date_time_approved='$date_time' WHERE ea_id='$ea_id'") or die (mysql_error());
?>