<?php

session_start();
date_default_timezone_set("Asia/Singapore");
include '../config.php';

function checkAdvAdj($adj_id) {
    include '../config.php';

    $sql_adj = mysqli_query($conn, "SELECT * FROM payment_adjustment WHERE adj_id='$adj_id'");
    $rs_adj = mysqli_fetch_array($sql_adj);
    if ($rs_adj['adj_type'] == 'add') {
        mysqli_query($conn, "UPDATE adv SET status='approved' WHERE ac_id='" . $rs_adj['ac_id'] . "'");
    } else {
        checkAdvIfPaid($rs_adj['payment_id'], $rs_adj['ac_id'], 'deduct');
    }
}

function checkAdvAdjDel($adj_id) {
    include '../config.php';

    $sql_adj = mysqli_query($conn, "SELECT * FROM payment_adjustment WHERE adj_id='$adj_id'");
    $rs_adj = mysqli_fetch_array($sql_adj);

    if ($rs_adj['ac_id'] != '0') {
        if ($rs_adj['adj_type'] == 'add') {
            mysqli_query($conn, "UPDATE adv SET status='approved' WHERE ac_id='" . $rs_adj['ac_id'] . "'");
        } else {
            checkAdvIfPaid($rs_adj['payment_id'], $rs_adj['ac_id'], 'deduct');
        }
    }
}

function checkTPAdj($adj_id, $tp_id) {
    include '../config.php';

    $sql_adj = mysqli_query($conn, "SELECT * FROM payment_adjustment WHERE adj_id='$adj_id'");
    $rs_adj = mysqli_fetch_array($sql_adj);

    if ($rs_adj['tp_id'] != 0 && $tp_id == 0) {
        truckPayStatus($rs_adj['tp_id'], 'delete');
    }
}

function cancelPay($payment_id, $charge_to, $remarks) {
    include '../config.php';

    $err = 0;
    $status = 'cancelled';
    $stmt = mysqli_prepare($conn, "UPDATE payment SET status=?,remarks=?,charge_to=? WHERE payment_id=?");
    $bind = mysqli_stmt_bind_param($stmt, "sssi", $status, $remarks, $charge_to, $payment_id);
    $exec = mysqli_stmt_execute($stmt);
    $err+= checkStmt($stmt);

    return $err;
}

function checkAdvIfPaid($payment_id, $ac_id, $type) {
    include '../config.php';

    $date_time = date("Y-m-d H:i:s");
    if ($type == 'add') {
        mysqli_query($conn, "UPDATE adv SET payment_id='$payment_id', status='issued', date_processed='$date_time' WHERE ac_id='$ac_id'");
    } else {
        $sql_adv = mysqli_query($conn, "SELECT * FROM adv WHERE ac_id='$ac_id'");
        $rs_count = mysqli_num_rows($sql_adv);
        $rs_adv = mysqli_fetch_array($sql_adv);
        if ($rs_count > 0) {
            $sql_adv_less = mysqli_query($conn, "SELECT sum(amount) FROM payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='$ac_id' and payment_adjustment.adj_type='deduct' and payment.status!='cancelled' and payment.status!='deleted'");
            $rs_adv_less = mysqli_fetch_array($sql_adv_less);

            $sql_adv_pay = mysqli_query($conn, "SELECT sum(amount) FROM adv_payment WHERE ac_id='$ac_id' and status!='cancelled'");
            $rs_adv_pay = mysqli_fetch_array($sql_adv_pay);

            $total_less = $rs_adv_pay['sum(amount)'] + $rs_adv_less['sum(amount)'];

            $total = $rs_adv['amount'] - $total_less;
            if ($total <= 0) {
                mysqli_query($conn, "UPDATE adv SET status='paid', date_paid='$date_time' WHERE ac_id='$ac_id'");
            } else {
                mysqli_query($conn, "UPDATE adv SET status='issued' WHERE ac_id='$ac_id'");
            }
        }
    }
}

function checkStmt($stmt) {
    include '../config.php';
    if ($stmt == false) {
        return 1;
    }
}

function checkTempPay() {
    include '../config.php';

    $err = 0;
    $sql_check = mysqli_query($conn, "SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
    $rs_check = mysqli_fetch_array($sql_check);
    if ($rs_check['sub_total'] === '0.00') {
        $err++;
    }
    return $err;
}

function maxId() {
    include '../config.php';

    $sql_id = mysqli_query($conn, "SELECT max(payment_id) FROM payment");
    $rs_id = mysqli_fetch_array($sql_id);
    $payment_id = $rs_id['max(payment_id)'];
    return $payment_id;
}

function saveTempPay($temp_data, $temp_data_adj, $temp_data_price_adj, $c) {
    include '../config.php';
    $err = 0;
    $stmt = mysqli_prepare($conn, "UPDATE `temp_payment` SET `bank_code`=?,`cheque_no`=?,`old_cheque_no`=?,`voucher_no`=?,`cheque_name`=?,`supplier_id`=?,`sub_total`=?,`ts_fee`=?,`adjustments`=?,`grand_total`=?,`account_name`=?,`account_number`=?,`type`=?,`pay_type`=?,`ap`=?,`verifier`=?,`signatory`=?,`remarks`=?,`charge_to`=?, `cheque_date`=? WHERE user_id=?");
    $bind = mysqli_stmt_bind_param($stmt, "sssssiddddssssssssssi", $temp_data['bank_code'], $temp_data['cheque_no'], $temp_data['old_cheque_no'], $temp_data['voucher_no'], $temp_data['cheque_name'], $temp_data['supplier_id'], $temp_data['sub_total'], $temp_data['ts_fee'], $temp_data['adjustments'], $temp_data['grand_total'], $temp_data['account_name'], $temp_data['account_number'], $temp_data['type'], $temp_data['pay_type'], $temp_data['ap'], $temp_data['verifier'], $temp_data['signatory'], $temp_data['remarks'], $temp_data['charge_to'], $temp_data['cheque_date'], $_SESSION['user_id']);
    $exec = mysqli_stmt_execute($stmt);

    $err+= checkStmt($stmt);

    $ctr = 0;
    while ($ctr < 5) {
        $stmt = mysqli_prepare($conn, "UPDATE `temp_payment_adjustment` SET  `adj_id`=?, `ac_id`=?, `tp_id`=?, `adj_type`=?, `desc`=?, `amount`=? WHERE  `user_id`=? and `adj_count`=?");
        $bind = mysqli_stmt_bind_param($stmt, "iiissdii", $temp_data_adj[$ctr]['adj_id'], $temp_data_adj[$ctr]['ac_id'], $temp_data_adj[$ctr]['tp_id'], $temp_data_adj[$ctr]['adj_type'], $temp_data_adj[$ctr]['desc'], $temp_data_adj[$ctr]['amount'], $temp_data_adj[$ctr]['user_id'], $temp_data_adj[$ctr]['adj_count']);
        $exec = mysqli_execute($stmt);
        $err+= checkStmt($stmt);
        $ctr++;
    }
    $ctr2 = 0;
    while ($ctr2 < $c) {
        if (isset($temp_data_price_adj[$ctr2]['cost']) && $temp_data_price_adj[$ctr2]['cost'] != '0.00') {
            $stmt = mysqli_prepare($conn, "UPDATE `temp_payment_price_adj` SET `detail_id`=?,`material_id`=?,`net_weight`=?,`cost`=?,`amount`=? WHERE `user_id`=? and `price_adj_count`=?");
            $bind = mysqli_stmt_bind_param($stmt, "siiddii", $temp_data_price_adj[$ctr2]['detail_id'], $temp_data_price_adj[$ctr2]['material_id'], $temp_data_price_adj[$ctr2]['net_weight'], $temp_data_price_adj[$ctr2]['cost'], $temp_data_price_adj[$ctr2]['amount'], $temp_data_price_adj[$ctr2]['user_id'], $temp_data_price_adj[$ctr2]['price_adj_count']);
            $exec = mysqli_execute($stmt);
            $err+= checkStmt($stmt);
        }
        $ctr2++;
    }
    return $err;
}

function saveAdtlTempPay($temp_data) {
    include '../config.php';

    $err = 0;
    $stmt = mysqli_prepare($conn, "UPDATE `temp_payment` SET `bank_code`=?,`cheque_no`=?,`old_cheque_no`=?,`voucher_no`=?,`cheque_name`=?,`account_name`=?,`account_number`=?,`cheque_date`=? WHERE user_id=?");
    $bind = mysqli_stmt_bind_param($stmt, "ssssssssi", $temp_data['bank_code'], $temp_data['cheque_no'], $temp_data['old_cheque_no'], $temp_data['voucher_no'], $temp_data['cheque_name'], $temp_data['account_name'], $temp_data['account_number'], $temp_data['cheque_date'], $_SESSION['user_id']);
    $exec = mysqli_stmt_execute($stmt);

    $err+= checkStmt($stmt);

    return $err;
}

function saveName($supplier_id, $cheque_name, $account_no) {
    include '../config.php';

    $stmt = mysqli_prepare($conn, "SELECT * FROM cheque_name WHERE supplier_id=? and name=?");
    $bind = mysqli_stmt_bind_param($stmt, "ss", $supplier_id, $cheque_name);
    $exec = mysqli_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);

    if ($count == 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO cheque_name (supplier_id, name) VALUES (?, ?)");
        $bind = mysqli_stmt_bind_param($stmt, "ss", $supplier_id, $cheque_name);
        $exec = mysqli_execute($stmt);
    }

    if ($account_no != '') {
        $stmt = mysqli_prepare($conn, "SELECT * FROM sup_bank_accounts WHERE supplier_id=? and account_name=? and account_number=?");
        $bind = mysqli_stmt_bind_param($stmt, "sss", $supplier_id, $cheque_name, $account_no);
        $exec = mysqli_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);

        if ($count == 0) {
            $stmt = mysqli_prepare($conn, "INSERT INTO sup_bank_accounts (supplier_id, account_name, account_number) VALUES (?, ?, ?)");
            $bind = mysqli_stmt_bind_param($stmt, "sss", $supplier_id, $cheque_name, $account_no);
            $exec = mysqli_execute($stmt);
        }
    }
}

function save($cheque_date, $trans_data) {
    include '../config.php';
    $err = 0;

    $stmt = mysqli_prepare($conn, "UPDATE `temp_payment` SET `cheque_date`=? WHERE user_id=?");
    $bind = mysqli_stmt_bind_param($stmt, "si", $cheque_date, $_SESSION['user_id']);
    $exec = mysqli_stmt_execute($stmt);

    $err+= checkStmt($stmt);

    $sql_save = mysqli_query($conn, "SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
    $rs_save = mysqli_fetch_array($sql_save);

    $stmt = mysqli_prepare($conn, "INSERT INTO payment (`bank_code`, `cheque_no`, `voucher_no`, `cheque_name`, `supplier_id`, `sub_total`, `ts_fee`, `adjustments`, `grand_total`, `account_name`, `account_number`, `type`, `pay_type`, `cancelled_cheque`, `ap`, `verifier`, `signatory`, `remarks`, `charge_to`, `date`, `time`, `cheque_date`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $err+= checkStmt($stmt);

    $bind = mysqli_stmt_bind_param($stmt, "ssssiddddsssssssssssss", $rs_save['bank_code'], $rs_save['cheque_no'], $rs_save['voucher_no'], $rs_save['cheque_name'], $rs_save['supplier_id'], $rs_save['sub_total'], $rs_save['ts_fee'], $rs_save['adjustments'], $rs_save['grand_total'], $rs_save['account_name'], $rs_save['account_number'], $rs_save['type'], $rs_save['pay_type'], $rs_save['old_cheque_no'], $rs_save['ap'], $rs_save['verifier'], $rs_save['signatory'], $rs_save['remarks'], $rs_save['charge_to'], date("Y/m/d"), date("H:i"), $rs_save['cheque_date']);
    $exec = mysqli_execute($stmt);

    saveName($rs_save['supplier_id'], $rs_save['cheque_name'], $rs_save['account_number']);

    $sql_save_adj = mysqli_query($conn, "SELECT * FROM temp_payment_adjustment WHERE user_id='" . $_SESSION['user_id'] . "'");
    while ($rs_save_adj = mysqli_fetch_array($sql_save_adj)) {
        if ($rs_save_adj['adj_type'] != '') {
            $stmt = mysqli_prepare($conn, "INSERT INTO `payment_adjustment`(`payment_id`, `ac_id`, `tp_id`, `adj_type`, `desc`, `amount`) VALUES (?,?,?,?,?,?)");
            $bind = mysqli_stmt_bind_param($stmt, "iiissd", maxId(), $rs_save_adj['ac_id'], $rs_save_adj['tp_id'], $rs_save_adj['adj_type'], $rs_save_adj['desc'], $rs_save_adj['amount']);
            $exec = mysqli_execute($stmt);

            truckPayStatus($rs_save_adj['tp_id'], 'paid');
            checkTPAdj($rs_save_adj['adj_id'], $rs_save_adj['tp_id']);
            checkAdvIfPaid(maxId(), $rs_save_adj['ac_id'], $rs_save_adj['adj_type']);
            $err+= checkStmt($stmt);
        }
        if ($rs_save_adj['adj_id'] != '0' && $rs_save_adj['adj_type'] == '') {
            checkAdvAdj($rs_save_adj['adj_id']);
            checkTPAdj($rs_save_adj['adj_id'], $rs_save_adj['tp_id']);
        }
    }

    $sql_save_price_adj = mysqli_query($conn, "SELECT * FROM temp_payment_price_adj WHERE user_id='" . $_SESSION['user_id'] . "'");
    while ($rs_save_price_adj = mysqli_fetch_array($sql_save_price_adj)) {
        if (!empty($rs_save_price_adj)) {
            $data = preg_split("[,]", $rs_save_price_adj['detail_id']);
            foreach ($data as $detail_id) {
                $sql_data = mysqli_query($conn, "SELECT * FROM scale_receiving_details WHERE detail_id='$detail_id'");
                $rs_data = mysqli_fetch_array($sql_data);
                $amount = 0;
                $total_amount = 0;
                $amount = $rs_data['corrected_weight'] * $rs_save_price_adj['cost'];
                $total_amount = ($rs_data['corrected_weight'] * $rs_save_price_adj['cost']) + $rs_data['amount'];
                mysqli_query($conn, "UPDATE scale_receiving_details SET adj_price='" . $rs_save_price_adj['cost'] . "',adj_amount='$amount',total_amount='$total_amount' WHERE detail_id='$detail_id'");
                mysqli_query($conn, "UPDATE scale_receiving SET upload='0',up_paper='0' WHERE trans_id='" . $rs_data['trans_id'] . "'");
            }
        }
    }

    $trans_array = preg_split("[_]", $trans_data);
    foreach ($trans_array as $trans_id) {
        mysqli_query($conn, "UPDATE scale_receiving SET payment_id='" . maxId() . "',voucher_no='" . $rs_save['voucher_no'] . "', cheque_no='" . $rs_save['cheque_no'] . "', status='paid', date_paid='" . date("Y/m/d") . "', upload='0', up_paper='0' WHERE trans_id='$trans_id'");
    }

    mysqli_query($conn, "UPDATE temp_payment SET status='saved' WHERE user_id='" . $_SESSION['user_id'] . "'");
}

function truckPayStatus($tp_id, $status) {
    include '../config.php';
    if ($status == 'paid') {
        mysqli_query($conn, "UPDATE truck_payment SET status='paid',date_paid='" . date("Y-m-d H:i:s") . "' WHERE tp_id='$tp_id'");
    } else {
        mysqli_query($conn, "UPDATE truck_payment SET status='paid',date_paid='0000-00-00 00:00:00' WHERE tp_id='$tp_id'");
    }
}

function update($payment_id, $cheque_date, $trans_data) {
    include '../config.php';
    $err = 0;

    $stmt = mysqli_prepare($conn, "UPDATE `temp_payment` SET `cheque_date`=? WHERE user_id=?");
    $bind = mysqli_stmt_bind_param($stmt, "si", $cheque_date, $_SESSION['user_id']);
    $exec = mysqli_stmt_execute($stmt);

    $err+= checkStmt($stmt);

    $sql_save = mysqli_query($conn, "SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
    $rs_save = mysqli_fetch_array($sql_save);

    $stmt = mysqli_prepare($conn, "UPDATE payment SET `bank_code`=?, `cheque_no`=?, `voucher_no`=?, `cheque_name`=?, `supplier_id`=?, `sub_total`=?, `ts_fee`=?, `adjustments`=?, `grand_total`=?, `account_name`=?, `account_number`=?, `type`=?, `pay_type`=?, `cancelled_cheque`=?, `ap`=?, `verifier`=?, `signatory`=?, `remarks`=?, `charge_to`=?, `cheque_date`=? WHERE payment_id=?");
    $err+= checkStmt($stmt);

    $bind = mysqli_stmt_bind_param($stmt, "ssssiddddsssssssssssi", $rs_save['bank_code'], $rs_save['cheque_no'], $rs_save['voucher_no'], $rs_save['cheque_name'], $rs_save['supplier_id'], $rs_save['sub_total'], $rs_save['ts_fee'], $rs_save['adjustments'], $rs_save['grand_total'], $rs_save['account_name'], $rs_save['account_number'], $rs_save['type'], $rs_save['pay_type'], $rs_save['old_cheque_no'], $rs_save['ap'], $rs_save['verifier'], $rs_save['signatory'], $rs_save['remarks'], $rs_save['charge_to'], $rs_save['cheque_date'], $payment_id);
    $exec = mysqli_execute($stmt);

    saveName($rs_save['supplier_id'], $rs_save['cheque_name'], $rs_save['account_number']);

    $sql_save_adj = mysqli_query($conn, "SELECT * FROM temp_payment_adjustment WHERE user_id='" . $_SESSION['user_id'] . "'");
    while ($rs_save_adj = mysqli_fetch_array($sql_save_adj)) {
        if ($rs_save_adj['adj_type'] != '') {
            if ($rs_save_adj['adj_id'] == '0') {
                $stmt = mysqli_prepare($conn, "INSERT INTO `payment_adjustment`(`payment_id`, `ac_id`, `tp_id`, `adj_type`, `desc`, `amount`) VALUES (?,?,?,?,?,?)");
                $bind = mysqli_stmt_bind_param($stmt, "iiissd", maxId(), $rs_save_adj['ac_id'], $rs_save_adj['tp_id'], $rs_save_adj['adj_type'], $rs_save_adj['desc'], $rs_save_adj['amount']);
                $exec = mysqli_execute($stmt);
                truckPayStatus($rs_save_adj['tp_id'], 'paid');
                checkAdvIfPaid(maxId(), $rs_save_adj['ac_id'], $rs_save_adj['adj_type']);
            } else {
                truckPayStatus($rs_save_adj['tp_id'], 'paid');
                checkTPAdj($rs_save_adj['adj_id'], $rs_save_adj['tp_id']);
                $stmt = mysqli_prepare($conn, "UPDATE `payment_adjustment` SET `payment_id`=?, `ac_id`=?, `tp_id`=?, `adj_type`=?, `desc`=?, `amount`=? WHERE adj_id=?");
                $bind = mysqli_stmt_bind_param($stmt, "iiissdi", maxId(), $rs_save_adj['ac_id'], $rs_save_adj['tp_id'], $rs_save_adj['adj_type'], $rs_save_adj['desc'], $rs_save_adj['amount'], $rs_save_adj['adj_id']);
                $exec = mysqli_execute($stmt);

                checkAdvIfPaid(maxId(), $rs_save_adj['ac_id'], $rs_save_adj['adj_type']);
            }
            $err+= checkStmt($stmt);
        }
        if ($rs_save_adj['adj_id'] != '0' && $rs_save_adj['adj_type'] == '') {
            checkAdvAdjDel($rs_save_adj['adj_id']);
            checkTPAdj($rs_save_adj['adj_id'], $rs_save_adj['tp_id']);

            mysql_query("DELETE FROM payment_adjustment WHERE adj_id='" . $rs_save_adj['adj_id'] . "'");
        }
    }

    $sql_save_price_adj = mysqli_query($conn, "SELECT * FROM temp_payment_price_adj WHERE user_id='" . $_SESSION['user_id'] . "'");
    while ($rs_save_price_adj = mysqli_fetch_array($sql_save_price_adj)) {
        if (!empty($rs_save_price_adj)) {
            $data = preg_split("[,]", $rs_save_price_adj['detail_id']);
            foreach ($data as $detail_id) {
                $sql_data = mysqli_query($conn, "SELECT * FROM scale_receiving_details WHERE detail_id='$detail_id'");
                $rs_data = mysqli_fetch_array($sql_data);
                $amount = 0;
                $total_amount = 0;
                $amount = $rs_data['corrected_weight'] * $rs_save_price_adj['cost'];
                $total_amount = ($rs_data['corrected_weight'] * $rs_save_price_adj['cost']) + $rs_data['amount'];
                mysqli_query($conn, "UPDATE scale_receiving_details SET adj_price='" . $rs_save_price_adj['cost'] . "',adj_amount='$amount',total_amount='$total_amount' WHERE detail_id='$detail_id'");
            }
        }
    }

    mysqli_query($conn, "UPDATE temp_payment SET status='saved' WHERE user_id='" . $_SESSION['user_id'] . "'");
}

$err = 0;

if (isset($_GET['payment']) && $_GET['payment'] == 'submitInitial') {
    $temp_data = array(
        'bank_code' => '',
        'cheque_no' => '',
        'old_cheque_no' => '',
        'voucher_no' => '',
        'cheque_name' => '',
        'supplier_id' => $_POST['supplier_id'],
        'sub_total' => $_POST['sub_total'],
        'ts_fee' => $_POST['ts_fee'],
        'adjustments' => $_POST['adjustments'],
        'grand_total' => round($_POST['grand_total'], 2),
        'account_name' => '',
        'account_number' => '',
        'type' => 'supplier',
        'pay_type' => 'Receiving',
        'ap' => $_SESSION['user_id'],
        'verifier' => $_SESSION['trade_verifier'],
        'signatory' => $_SESSION['trade_signatory'],
        'remarks' => '',
        'charge_to' => '',
        'cheque_date' => ''
    );


    $temp_data_adj = array();

    $c = 1;
    while ($c <= 5) {
        $data_adj = array(
            'adj_id' => $_POST['adj_id_' . $c],
            'ac_id' => $_POST['ac_id_' . $c],
            'tp_id' => $_POST['tp_id_' . $c],
            'adj_type' => $_POST['adj_' . $c],
            'desc' => strtoupper($_POST['desc_' . $c]),
            'amount' => $_POST['amount_' . $c],
            'user_id' => $_SESSION['user_id'],
            'adj_count' => $c
        );
        array_push($temp_data_adj, $data_adj);
        unset($data_adj);
        $c++;
    }

    $temp_data_price_adj = array();

    $c = 1;
    $ctr = $_GET['c'];
    while ($c < $ctr) {
        if ($_POST['cost' . $c] != '') {
            $price_adj = array(
                'user_id' => $_SESSION['user_id'],
                'detail_id' => $_POST['detail_id' . $c],
                'material_id' => $_POST['material_id' . $c],
                'net_weight' => $_POST['net_weight' . $c],
                'cost' => $_POST['cost' . $c],
                'amount' => $_POST['amount' . $c],
                'price_adj_count' => $c
            );
            array_push($temp_data_price_adj, $price_adj);
            unset($price_adj);
        }
        $c++;
    }

    $err+= saveTempPay($temp_data, $temp_data_adj, $temp_data_price_adj, $c);
}



if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinal') {
    if (checkTempPay() == 0) {
        $temp_data = array(
            'bank_code' => $_POST['bank_code'],
            'cheque_no' => $_POST['cheque_no'],
            'old_cheque_no' => $_POST['old_cheque_no'],
            'voucher_no' => $_POST['voucher_no'],
            'cheque_name' => strtoupper($_POST['cheque_name']),
            'account_name' => strtoupper($_POST['cheque_name']),
            'account_number' => $_POST['account_number'],
            'cheque_date' => $_POST['cheque_date']
        );

        $err+= saveAdtlTempPay($temp_data);
    } else {
        echo "<font color = 'red'>Some error occur processing this transaction, Please try again.</font>";
    }
}

if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalSave') {
    $err+= save($_POST['cheque_date'], $_POST['trans_array']);
}


if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalEditSave') {
    $err+= cancelPay($_GET['payment_id'], $_GET['charge_to'], $_GET['remarks']);

    $err+= save($_POST['cheque_date'], $_POST['trans_array']);
}

if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalEditUpdate') {
    $err+= update($_GET['payment_id'], $_POST['cheque_date'], $_POST['trans_array']);
}

if (isset($_GET['payment']) && $_GET['payment'] == 'getIdSaved') {
    echo maxId();
}

if ($err > 0) {
    echo "<font color = 'red'>Some data not save correctly in database, Please contact your system admin immediately.</font>";
} else {
    if (isset($_GET['payment']) && ($_GET['payment'] == 'submitFinalSave') || isset($_GET['payment']) && ($_GET['payment'] == 'submitFinalEditSave') || isset($_GET['payment']) && ($_GET['payment'] == 'submitFinalEditUpdate')) {
        echo "<font color = 'red'>Payment saved.</font>";
    }
}
?>