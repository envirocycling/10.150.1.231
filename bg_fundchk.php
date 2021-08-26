<?php
		$page = "bg_fundchk.php";
		$sec = "300";
		header("Refresh: $sec; url=$page");
date_default_timezone_set("Asia/Singapore");
include('config.php');

$sql_time = mysql_query("SELECT * from system_settings") or die(mysql_error());
$row_time = mysql_fetch_array($sql_time);

echo $myTime = date('H');
echo '<br>';
echo $time = date('H',strtotime($row_time['fund_cutofftime']));
$date = date('Y/m/d');

$sql_available = mysql_query("SELECT * from fund_available WHERE date='$date' and amount > 0") or die(mysql_error());

if($_GET['re_update'] == '1'){
	mysql_query("UPDATE branches SET status='' WHERE branch_id!='7' and branch_id!='10'") or die(mysql_error());
		$page2 = "bg_fund.php";
		$sec2 = "180";
		header("Refresh: $sec2; url=$page2");
}else if(($myTime == $time) && (mysql_num_rows($sql_available) == 0)){
		$page2 = "bg_fund.php";
		$sec2 = "600";
		header("Refresh: $sec2; url=$page2");
	
}
?>