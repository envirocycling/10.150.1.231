<?php
date_default_timezone_set("Asia/Singapore");
include("../config.php");

$ea_id = $_POST['ea_id'];
//$total_expense = $_POST['total_expense'];
$pcv_no= $_POST['pcv_no'];
$excess_cash = $_POST['excess_cash'];
$return_cash = $_POST['return_cash'];
$details = mysql_real_escape_string($_POST['details']);
//$description = mysql_real_escape_string($_POST['description']);
//$specify = mysql_real_escape_string($_POST['specify']);
$amount = $_POST['amount'];
$date = date('Y-m-d H:i:s');

$sql_pcv = mysql_query("SELECT * from system_settings") or die(mysql_error());
$row_pcv = mysql_fetch_array($sql_pcv);
$next_pcv1 = $row_pcv['ea_pcv_no'] + 1 ;
$next_pcv = sprintf('%02u', ($next_pcv1));
$sql_chk = mysql_query("SELECT * from employee_advances WHERE pcv_no = '$pcv_no' ") or die(mysql_error());
/*if($details == 'OTHERS'){
	mysql_query("INSERT INTO employee_advances_details (details) VALUES ('$specify')") or die(mysql_error());
		
		if(mysql_query("UPDATE employee_advances SET date_liquidated='$date', status='liquidated', total_expense='$total_expense', excess_cash='$excess_cash', pcv_no='$pcv_no' WHERE ea_id='$ea_id'")){
			mysql_query("INSERT INTO employee_advances_liquidate (ea_id, details, description, amount) VALUES('$ea_id', '$specify', '$description', '$amount')") or die(mysql_error());
		}
}else if(!empty($details)){*/
if(mysql_num_rows($sql_chk) == 0){
			if(mysql_query("UPDATE employee_advances SET date_liquidated='$date', status='liquidated', total_expense='$amount', excess_cash='$excess_cash', pcv_no='$pcv_no', returned_excess_cash='$return_cash' WHERE ea_id='$ea_id'")){
                            mysql_query("INSERT INTO employee_advances_liquidate (ea_id, details, description, amount) VALUES('$ea_id', '$details', '', '$amount')") or die(mysql_error());
                            mysql_query("UPDATE system_settings SET `ea_pcv_no` = '$next_pcv'") or die(mysql_error());
			}
}else{
    echo 'input pcv';
}
//}
                     
?>