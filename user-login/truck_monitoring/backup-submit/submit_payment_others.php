<?php

@session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';

$sql_id = mysql_query("SELECT max(payment_id) FROM payment");
$rs_id = mysql_fetch_array($sql_id);
$payment_id = $rs_id['max(payment_id)'] + 1;
$cheque_name = mysql_real_escape_string($_POST['cheque_name']);

//$cheque_name = utf8_decode($cheque_name);
mysql_query("UPDATE temp_payment SET bank_code='" . $_POST['account'] . "', cheque_no='" . $_POST['cheque_no'] . "', old_cheque_no='" . $_POST['old_cheque_no'] . "', voucher_no='" . $_POST['voucher_no'] . "', cheque_name='$cheque_name', sub_total='', ts_fee='', adjustments='', grand_total='" . $_POST['grand_total'] . "', type='" . $_POST['type'] . "', ap='" . $_SESSION['user_id'] . "', verifier='" . $_SESSION['verifier'] . "', signatory='" . $_SESSION['signatory'] . "',cheque_date='" . $_POST['cheque_date'] . "' WHERE user_id='" . $_SESSION['user_id'] . "'");
$c = 1;
while ($c <= 20) {
    $particular = mysql_real_escape_string($_POST['particular_' . $c]);
//    $particular = utf8_decode($particular);

    mysql_query("UPDATE `temp_payment_others` SET `particulars` =  '$particular',
`quantity` =  '" . $_POST['quantity_' . $c] . "',
`unit_price` =  '" . $_POST['unit_price_' . $c] . "',
`amount` =  '" . $_POST['amount_' . $c] . "' WHERE  `user_id`='" . $_SESSION['user_id'] . "' and `others_count` =  '$c'");
    $c++;
}
?>