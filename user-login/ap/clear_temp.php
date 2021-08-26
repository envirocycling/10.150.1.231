<?php

session_start();
include 'config.php';

mysql_query("UPDATE `temp_payment` SET `bank_code`='',`cheque_no`='',`old_cheque_no`='',`voucher_no`='',`cheque_name`='',`supplier_id`='',`sub_total`='',`ts_fee`='',`adjustments`='',`grand_total`='',`account_name`='',`account_number`='',`type`='',`pay_type`='', `status`='', `ap`='',`verifier`='',`remarks`='',`charge_to`='',`signatory`='',`cheque_date`='' WHERE user_id='" . $_SESSION['user_id'] . "'");
mysql_query("UPDATE `temp_payment_adjustment` SET `adj_id`='',`ac_id`='',`tp_id`='',`adj_type`='',`desc`='',`amount`='' WHERE user_id='" . $_SESSION['user_id'] . "'");
mysql_query("UPDATE `temp_payment_others` SET `others_id`='',`particulars`='',`quantity`='',`unit_price`='',`amount`='' WHERE user_id='" . $_SESSION['user_id'] . "'");
mysql_query("UPDATE `temp_payment_price_adj` SET `detail_id`='',`material_id`='',`net_weight`='',`cost`='',`amount`='' WHERE user_id='" . $_SESSION['user_id'] . "'");

$sql = mysql_query("SELECT MAX( payment_id ) FROM  `payment` WHERE `status`!='cancelled'");
$rs = mysql_fetch_array($sql);

$sql_c = mysql_query("SELECT * FROM payment WHERE payment_id='" . $rs['MAX( payment_id )'] . "'");
$rs_c = mysql_fetch_array($sql_c);

if (!isset($_POST['type'])) {
    if ($rs_c['bank_code'] == 'SBC') {
        echo "<script>";
        echo "location.replace('send_payments.php?pay_id=" . $rs['MAX( payment_id )'] . "');";
        echo "</script>";
    } else {
        echo "<script>";
        echo "location.replace('index.php');";
        echo "</script>";
    }
}
?>