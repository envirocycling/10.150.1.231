<?php
session_start();
date_default_timezone_set("Asia/Singapore");
include("../config.php");

$date = $_POST['date'];
$ref_no = $_POST['ref_no'];
$emp_id = $_POST['emp_id'];
$amount = $_POST['amount'];
$purpose = mysql_real_escape_string($_POST['purpose']);
$approver = $_POST['approve_id'];
$company = $_POST['company'];
$edit = $_POST['edit'];
$up = $_POST['up'];
$ea_id = $_POST['ea_id'];
$date_received = date('Y-m-d H:i:s');

$sql_branch = mysql_query("SELECT * from branches WHERE branch_name LIKE '%".$_SESSION['branch']."%'");
$row_branch = mysql_fetch_array($sql_branch);

if($up == '1'){
	mysql_query("UPDATE employee_advances SET status='issued', date_received='$date_received' WHERE ea_id='$ea_id'") or die(mysql_error());
}else if($edit == '1'){
	mysql_query("UPDATE employee_advances SET date='$date', branch_id='".$row_branch['branch_id']."', comp_id='$company', emp_id='$emp_id', amount='$amount', purpose='$purpose', approver='$approver' WHERE ea_id='$ea_id'") or die(mysql_error());
}else{
        if(mysql_query("INSERT INTO employee_advances (emp_id, ref_no, amount, purpose, date, approver, status,prepared_by, branch_id, comp_id) VALUES ('$emp_id', '$ref_no', '$amount', '$purpose', '$date', '$approver', 'pending','".$_SESSION['ap_id']."','".$row_branch['branch_id']."','$company')") or die(mysql_error())){
            $sql_ref_no = mysql_query("SELECT * from system_settings");
            $row_ref_no = mysql_fetch_array($sql_ref_no);
            $new_ref_no = $row_ref_no['ea_ref_no'] + 1;
            $next_ref_no = sprintf('%04u', ($new_ref_no));
            mysql_query("UPDATE system_settings SET ea_ref_no='$next_ref_no'");
        }
}
?>