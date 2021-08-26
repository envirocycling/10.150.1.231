<?php
date_default_timezone_set("Asia/Singapore");
session_start();
include('../config.php');

$date_updated = date('Y/m/d');
$user_id = $_SESSION['ic_id'];
$branch_id = $_POST['branch_id'];
$budget = $_POST['budget'];
$from = $_POST['from'];
$to = $_POST['to'];

$sql_chk = mysql_query("SELECT * from fund_weeklybudget WHERE `branch_id`='$branch_id' and `from`='$from' and `to`='$to'") or die(mysql_error());
if(mysql_num_rows($sql_chk) == 0){
    mysql_query("INSERT INTO fund_weeklybudget (`branch_id`, `budget`, `from`, `to`, `user_id`, `date_updated`) VALUES ('$branch_id', '$budget', '$from', '$to', '$user_id', '$date_updated')") or die(mysql_error());
}else{
    mysql_query("UPDATE fund_weeklybudget SET `branch_id`='$branch_id', `budget`='$budget', `from`='$from', `to`='$to', `user_id`='$user_id', `date_updated`='$date_updated' WHERE `branch_id`='$branch_id' and `from`='$from' and `to`='$to'") or die(mysql_error());
}
?>