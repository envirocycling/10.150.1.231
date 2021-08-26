<?php

@session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';

$sql_online = mysql_query("SELECT * FROM p_online WHERE online_id='1'");
$rs_online = mysql_fetch_array($sql_online);

$cheque_name = mysql_real_escape_string($_POST['cheque_name']);
//$cheque_name = utf8_decode($cheque_name);

mysql_query("UPDATE temp_payment SET bank_code='" . $_POST['account'] . "', cheque_no='" . $_POST['cheque_no'] . "', voucher_no='" . $_POST['voucher_no'] . "', cheque_name='$cheque_name', supplier_id='" . $_POST['supplier_id'] . "', account_name='$cheque_name', account_number='" . $_POST['account_num'] . "', ap='" . $_SESSION['user_id'] . "', verifier='" . $_SESSION['verifier'] . "', signatory='" . $_SESSION['signatory'] . "' WHERE user_id='" . $_SESSION['user_id'] . "'");
?>