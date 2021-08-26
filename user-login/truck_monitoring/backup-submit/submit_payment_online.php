<?php
date_default_timezone_set("Asia/Singapore");
include 'config.php';

$sql_id = mysql_query("SELECT max(payment_id) FROM payment");
$rs_id = mysql_fetch_array($sql_id);
$payment_id = $rs_id['max(payment_id)'] + 1;

$sql_save = mysql_query("SELECT * FROM temp_payment WHERE temp_id='1'");
$rs_save = mysql_fetch_array($sql_save);
$sql_check = mysql_query("SELECT * FROM payment WHERE cheque_no='" . $rs_save['cheque_no'] . "'");
$rs_count = mysql_num_rows($sql_check);
$rs_check = mysql_fetch_array($sql_check);

$sql_check_name = mysql_query("SELECT * FROM cheque_name WHERE name='" . $rs_save['cheque_name'] . "' and supplier_id='" . $rs_save['supplier_id'] . "'");
$rs_count_name = mysql_num_rows($sql_check_name);
$rs_check_name = mysql_fetch_array($sql_check_name);
if ($rs_count == 0) {
//    mysql_query("UPDATE payment SET bank_code='" . $rs_save['bank_code'] . "', voucher_no='" . $rs_save['voucher_no'] . "', cheque_name='" . $rs_save['cheque_name'] . "', sub_total='" . $rs_save['sub_total'] . "', ts_fee='" . $rs_save['ts_fee'] . "', adjustments='" . $rs_save['adjustments'] . "', grand_total='" . $rs_save['grand_total'] . "', date='" . date("Y/m/d") . "' WHERE cheque_no='" . $rs_check['cheque_no'] . "'");
//} else {
    mysql_query("INSERT INTO payment (`bank_code`, `cheque_no`, `voucher_no`, `cheque_name`, `supplier_id`, `sub_total`, `ts_fee`, `adjustments`, `grand_total`, `type`, `ap`, `verifier`, `signatory`, `date`)
    VALUES ('" . $rs_save['bank_code'] . "','" . $rs_save['cheque_no'] . "','" . $rs_save['voucher_no'] . "','" . $rs_save['cheque_name'] . "','" . $rs_save['supplier_id'] . "','" . $rs_save['sub_total'] . "','" . $rs_save['ts_fee'] . "','" . $rs_save['adjustments'] . "','" . $rs_save['grand_total'] . "', 'supplier', '" . $rs_save['ap'] . "', '" . $rs_save['verifier'] . "', '" . $rs_save['signatory'] . "', '" . date("Y/m/d") . "')");
    $sql_save_adj = mysql_query("SELECT * FROM temp_payment_adjustment");
    while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
        if (!empty($rs_save_adj['adj_type'])) {
            mysql_query("INSERT INTO `payment_adjustment` (`payment_id`, `adj_type`, `desc`, `amount`) VALUES ('$payment_id','" . $rs_save_adj['adj_type'] . "','" . $rs_save_adj['desc'] . "','" . $rs_save_adj['amount'] . "')");
//            mysql_query("INSERT INTO payment_adjustment (payment_id,adj_type,desc,amount) VALUES ('$payment_id','" . $rs_save_adj['adj_type'] . "','" . $rs_save_adj['desc'] . "','" . $rs_save_adj['amount'] . "')");
        }
    }

    if ($rs_count_name == 0) {
        mysql_query("INSERT INTO cheque_name (supplier_id, name) VALUES ('" . $rs_save['supplier_id'] . "','" . $rs_save['cheque_name'] . "')");
    }

    $trans_array = preg_split("[_]", $_POST['tras_array']);
    foreach ($trans_array as $trans_id) {
        mysql_query("UPDATE scale_receiving SET payment_id='$payment_id',voucher_no='" . $rs_save['voucher_no'] . "', cheque_no='" . $rs_save['cheque_no'] . "', status='paid', date_paid='" . date("Y/m/d") . "' WHERE trans_id='$trans_id'");
    }
}
?>