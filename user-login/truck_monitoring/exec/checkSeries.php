<?php

date_default_timezone_set("Asia/Singapore");
session_start();
include '../config.php';

$user_id = $_SESSION['user_id'];

function checkBankIfUsed($bank_code, $user_id) {
    $sql = mysql_query("SELECT * FROM temp_payment WHERE bank_code='$bank_code' and user_id!='$user_id'");
    $count = mysql_num_rows($sql);
    return $count;
}

function checkChequeNo($bank_code, $cheque_no) {
    $sql_check = mysql_query("SELECT count(payment_id) FROM payment WHERE bank_code='$bank_code' and cheque_no='$cheque_no' and cheque_status!='issued' and status!='deleted'");
    $rs_check = mysql_fetch_array($sql_check);
    return $rs_check['count(payment_id)'];
}

function checkSeries($bank_code) {
    $sql_che = mysql_query("SELECT max(cheque_no) FROM payment WHERE bank_code='$bank_code' and cheque_status!='issued' and status!='deleted'");
    $rs_che = mysql_fetch_array($sql_che);

    $sql_c_r = mysql_query("SELECT * FROM cheque_range WHERE bank_code='$bank_code' and status=''");
    $rs_c_r_c = mysql_num_rows($sql_c_r);
    $rs_c_r = mysql_fetch_array($sql_c_r);

    $chequeNo_array = array("");

    if ($rs_c_r_c == '' || $rs_c_r_c == 0 || $rs_che['max(cheque_no)'] >= $rs_c_r['to']) {
        $cheque_no = "Range Error";
        array_push($chequeNo_array, $cheque_no);
    } else {
        $from = $rs_c_r['from'];
        $to = $rs_c_r['to'];
        while ($from <= $to) {
            $cheque_no = sprintf("%010s", $from);
            if (checkChequeNo($bank_code, $cheque_no) == 0) {
                array_push($chequeNo_array, $cheque_no);
            }
            $from++;
        }
    }
    return json_encode($chequeNo_array);
}

function checkSeries2($bank_code) {
    $date = date("Y/m/d");
    $voucher_date = date("md");

    $sql_pay = mysql_query("SELECT count(voucher_no),max(voucher_no) FROM payment WHERE bank_code='$bank_code' and cheque_status!='issued' and status!='deleted' and date='$date'");
    $rs_pay = mysql_fetch_array($sql_pay);
    if ($rs_pay['count(voucher_no)'] == '0') {
        $voucher_number = "01";
    } else {
        $details = preg_split("[-]", $rs_pay['max(voucher_no)']);
        $voucher_number = $details[1] + 1;
        if ($voucher_number < 10) {
            $voucher_number = "0" . $voucher_number;
        }
    }
    $voucher_no = $voucher_date . "-" . $voucher_number;
    return $voucher_no;
}

if ($_GET['type'] == 'cheque') {
    if (checkBankIfUsed($_POST['bank_code'], $user_id) > 0) {
        echo "Used";
    } else {
        $sql = mysql_query("SELECT * FROM temp_payment WHERE user_id='$user_id'");
        $rs = mysql_fetch_array($sql);
        if ($rs['grand_total'] == '0.00' && $rs['pay_type'] == 'Receiving') {
            $chequeNo_array = array("","000000000");
            echo json_encode($chequeNo_array);
        } else {
            echo checkSeries($_POST['bank_code']);
        }
    }
}

if ($_GET['type'] == 'voucher') {
    echo checkSeries2($_POST['bank_code']);
}
?>