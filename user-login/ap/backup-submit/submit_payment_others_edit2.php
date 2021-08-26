<?php

@session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';
$my_date = date("Y/m/d");
$sql_id = mysql_query("SELECT max(payment_id) FROM payment");
$rs_id = mysql_fetch_array($sql_id);
$payment_id = $rs_id['max(payment_id)'] + 1;

$sql_save = mysql_query("SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
$rs_save = mysql_fetch_array($sql_save);

$cheque_name = mysql_real_escape_string($rs_save['cheque_name']);

$sql_check_name = mysql_query("SELECT * FROM cheque_name WHERE name='$cheque_name'");
$rs_count_name = mysql_num_rows($sql_check_name);
$rs_check_name = mysql_fetch_array($sql_check_name);

if ($rs_count_name == 0) {
    mysql_query("INSERT INTO cheque_name (name) VALUES ('$cheque_name')");
}

$sql_check = mysql_query("SELECT * FROM payment WHERE cheque_no='" . $rs_save['cheque_no'] . "'");
$rs_count = mysql_num_rows($sql_check);
$rs_check = mysql_fetch_array($sql_check);
if ($rs_count == 0) {

    mysql_query("INSERT INTO payment (`bank_code`, `cheque_no`, `voucher_no`, `cheque_name`, `sub_total`, `ts_fee`, `adjustments`, `grand_total`, `type`, `pay_type`, `cancelled_cheque`, `date`, `cheque_date`)
    VALUES ('" . $rs_save['bank_code'] . "','" . $rs_save['cheque_no'] . "','" . $rs_save['voucher_no'] . "','$cheque_name','" . $rs_save['sub_total'] . "','" . $rs_save['ts_fee'] . "','" . $rs_save['adjustments'] . "','" . $rs_save['grand_total'] . "', '" . $rs_save['type'] . "', 'Other Payment','" . $rs_save['old_cheque_no'] . "', '" . date("Y/m/d") . "','" . $rs_save['cheque_date'] . "')");

    $sql_save_adj = mysql_query("SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "'");
    while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
        if (!empty($rs_save_adj['particulars'])) {
            $particular = mysql_real_escape_string($rs_save_adj['particulars']);
            mysql_query("INSERT INTO payment_others (payment_id,particulars,quantity,unit_price,amount) VALUES ('$payment_id','$particular','" . $rs_save_adj['quantity'] . "','" . $rs_save_adj['unit_price'] . "','" . $rs_save_adj['amount'] . "')");
        }
    }
    mysql_query("UPDATE payment SET status='cancelled',charge_to='" . $_SESSION['user_id'] . "' WHERE payment_id='" . $_POST['payment_id'] . "'");
} else {

    mysql_query("UPDATE payment SET cheque_no='" . $rs_save['cheque_no'] . "',voucher_no='" . $rs_save['voucher_no'] . "',cheque_name='$cheque_name',sub_total='" . $rs_save['sub_total'] . "',ts_fee='" . $rs_save['ts_fee'] . "',adjustments='" . $rs_save['adjustments'] . "',grand_total='" . $rs_save['grand_total'] . "',charge_to='" . $_SESSION['user_id'] . "' WHERE payment_id='" . $_POST['payment_id'] . "'");

    $sql_save_adj = mysql_query("SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "'");
    while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
        if (!empty($rs_save_adj['particulars'])) {
            $particular = mysql_real_escape_string($rs_save_adj['particulars']);

            if ($rs_save_adj['others_id'] == '') {
                mysql_query("INSERT INTO payment_others (payment_id,particulars,quantity,unit_price,amount) VALUES ('" . $_POST['payment_id'] . "','$particular','" . $rs_save_adj['quantity'] . "','" . $rs_save_adj['unit_price'] . "','" . $rs_save_adj['amount'] . "')");
            } else {
                mysql_query("UPDATE payment_others SET particulars='$particular',quantity='" . $rs_save_adj['quantity'] . "',unit_price='" . $rs_save_adj['unit_price'] . "',amount='" . $rs_save_adj['amount'] . "' WHERE id='" . $rs_save_adj['others_id'] . "'");
            }
        }
    }
}
?>