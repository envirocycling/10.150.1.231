<?php

@session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';

$sql_id = mysql_query("SELECT max(payment_id) FROM payment");
$rs_id = mysql_fetch_array($sql_id);
$payment_id = $rs_id['max(payment_id)'] + 1;
if ($_POST['charge'] == 'AP') {
    $charge = $_SESSION['user_id'];
} else {
    $charge = $_POST['charge'];
}

$cheque_name = mysql_real_escape_string($_POST['cheque_name']);
//$cheque_name = utf8_decode($cheque_name);

if (isset($_POST['remarks'])) {
    mysql_query("UPDATE temp_payment SET cheque_no='" . $_POST['cheque_number'] . "', voucher_no='" . $_POST['voucher_number'] . "', cheque_name='$cheque_name', sub_total='" . $_POST['sub_total'] . "', ts_fee='" . $_POST['ts_fee'] . "', adjustments='" . $_POST['adjustment'] . "', grand_total='" . $_POST['grand_total'] . "', remarks='" . $_POST['remarks'] . "', charge_to='$charge' WHERE user_id='" . $_SESSION['user_id'] . "'");
} else {
    mysql_query("UPDATE temp_payment SET cheque_no='" . $_POST['cheque_number'] . "', voucher_no='" . $_POST['voucher_number'] . "', cheque_name='$cheque_name', sub_total='" . $_POST['sub_total'] . "', ts_fee='" . $_POST['ts_fee'] . "', adjustments='" . $_POST['adjustment'] . "', grand_total='" . $_POST['grand_total'] . "', remarks='', charge_to='' WHERE user_id='" . $_SESSION['user_id'] . "'");
}
$c = 1;
while ($c <= 5) {
    $description = mysql_real_escape_string($_POST['desc_' . $c]);
//    $description = utf8_decode($description);

    mysql_query("UPDATE `temp_payment_adjustment` SET  `adj_id`='" . $_POST['adj_id_' . $c] . "',`ac_id`='" . $_POST['ac_id_' . $c] . "',`adj_type` =  '" . $_POST['adj_' . $c] . "',
`desc` =  '$description',
`amount` =  '" . $_POST['amount_' . $c] . "' WHERE `user_id`='" . $_SESSION['user_id'] . "' and `adj_count` =  '$c'");
    $c++;
}
?>