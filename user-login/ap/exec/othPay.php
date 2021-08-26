<?php

session_start();
date_default_timezone_set("Asia/Singapore");
include '../config.php';

function cancelPay($payment_id, $user_id) {
    include '../config.php';
    $status = 'cancelled';
    $stmt = mysqli_prepare($conn, "UPDATE payment SET status=?,charge_to=? WHERE payment_id=?");
    $bind = mysqli_stmt_bind_param($stmt, "ssi", $status, $user_id, $payment_id);
    $exec = mysqli_stmt_execute($stmt);
}

function checkStmt($stmt) {
    include '../config.php';
    if ($stmt == false) {
        return 1;
    }
}

function maxId() {
    include '../config.php';

    $sql_id = mysqli_query($conn, "SELECT max(payment_id) FROM payment");
    $rs_id = mysqli_fetch_array($sql_id);
    $payment_id = $rs_id['max(payment_id)'];
    return $payment_id;
}

function save($user_id) {
    include '../config.php';
    $err = 0;
    $sql_save = mysqli_query($conn, "SELECT * FROM temp_payment WHERE user_id='$user_id'");
    $rs_save = mysqli_fetch_array($sql_save);

    $stmt = mysqli_prepare($conn, "INSERT INTO payment (`bank_code`, `cheque_no`, `voucher_no`, `cheque_name`, `cheque_name2`, `supplier_id`, `description`, `sub_total`, `ts_fee`, `adjustments`, 
            `grand_total`, `account_name`, `account_number`, `type`, `pay_type`, `cancelled_cheque`, `ap`, `verifier`, `signatory`, `remarks`, `ft`, `charge_to`, `date`, `time`, 
            `cheque_date`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $err+= checkStmt($stmt);
    
    saveName(str_replace('[AMPERSAND]','&',$rs_save['cheque_name']));
    saveName2(str_replace('[AMPERSAND]','&',$rs_save['cheque_name2']));

    $bind = mysqli_stmt_bind_param($stmt, "sssssisddddssssssssisssss", $rs_save['bank_code'], $rs_save['cheque_no'], $rs_save['voucher_no'], str_replace('[AMPERSAND]','&',$rs_save['cheque_name']),  str_replace('[AMPERSAND]','&',$rs_save['cheque_name2']), $rs_save['supplier_id'], $rs_save['description'], $rs_save['sub_total'], $rs_save['ts_fee'], $rs_save['adjustments'], $rs_save['grand_total'], $rs_save['account_name'], $rs_save['account_number'], $rs_save['type'], $rs_save['pay_type'], $rs_save['old_cheque_no'], $rs_save['ap'], $rs_save['verifier'], $rs_save['signatory'], $rs_save['remarks'], $rs_save['ft'], $rs_save['charge_to'], date("Y/m/d"), date("H:i"), $rs_save['cheque_date']);
    $exec = mysqli_execute($stmt);

    $sql_save_adj = mysqli_query($conn, "SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "'");
    while ($rs_save_adj = mysqli_fetch_array($sql_save_adj)) {
        if ($err == 0 && (!empty($rs_save_adj['particulars']) || !empty($rs_save_adj['description']))) {
            $stmt = mysqli_prepare($conn, "INSERT INTO payment_others (payment_id,particulars,description,quantity,unit_price,amount) VALUES (?,?,?,?,?,?)");
            $err+= checkStmt($stmt);
            $bind = mysqli_stmt_bind_param($stmt, "issddd", maxId(), $rs_save_adj['particulars'], $rs_save_adj['description'], $rs_save_adj['quantity'], $rs_save_adj['unit_price'], $rs_save_adj['amount']);
            $exec = mysqli_execute($stmt);
        }
    }

    return $err;
}

function saveTempPay($temp_data, $temp_data_details) {
    include '../config.php';
    $err = 0;

    $stmt = mysqli_prepare($conn, "UPDATE `temp_payment` SET `bank_code`=?,`cheque_no`=?,`old_cheque_no`=?,`voucher_no`=?,`cheque_name`=?,`cheque_name2`=?,`supplier_id`=?,`description`=?,
            `sub_total`=?,`ts_fee`=?,`adjustments`=?,`grand_total`=?,`account_name`=?,`account_number`=?,`type`=?,`pay_type`=?,`ap`=?,`verifier`=?,`signatory`=?,`remarks`=?,`ft`=?,
            `charge_to`=?, `cheque_date`=? WHERE user_id=?");
    $bind = mysqli_stmt_bind_param($stmt, "ssssssisddddsssssssisssi", $temp_data['bank_code'], $temp_data['cheque_no'], $temp_data['old_cheque_no'], $temp_data['voucher_no'], str_replace('[AMPERSAND]','&',$temp_data['cheque_name']), str_replace('[AMPERSAND]','&',$temp_data['cheque_name2']), $temp_data['supplier_id'], $temp_data['description'], $temp_data['sub_total'], $temp_data['ts_fee'], $temp_data['adjustments'], $temp_data['grand_total'], $temp_data['account_name'], $temp_data['account_number'], $temp_data['type'], $temp_data['pay_type'], $temp_data['ap'], $temp_data['verifier'], $temp_data['signatory'], $temp_data['remarks'], $temp_data['ft'], $temp_data['charge_to'], $temp_data['cheque_date'], $_SESSION['user_id']);
    $exec = mysqli_stmt_execute($stmt);

    $err+= checkStmt($stmt);

    $ctr = 0;
    while ($ctr < 20) {
        $stmt = mysqli_prepare($conn, "UPDATE `temp_payment_others` SET `others_id`=?,`particulars`=?, `description`=?, `quantity`=?, `unit_price`=?, `amount`=? WHERE `user_id`=? and `others_count`=?");
        $bind = mysqli_stmt_bind_param($stmt, "issdddii", $temp_data_details[$ctr]['others_id'], $temp_data_details[$ctr]['particulars'], $temp_data_details[$ctr]['description'], $temp_data_details[$ctr]['quantity'], $temp_data_details[$ctr]['unit_price'], $temp_data_details[$ctr]['amount'], $temp_data_details[$ctr]['user_id'], $temp_data_details[$ctr]['others_count']);
        $exec = mysqli_execute($stmt);
        $err+= checkStmt($stmt);
        $ctr++;
    }
    return $err;
}

function saveName($cheque_name) {
    include '../config.php';
    
    $stmt = mysqli_prepare($conn, "SELECT * FROM cheque_name WHERE name=?");
    $bind = mysqli_stmt_bind_param($stmt, "s", $cheque_name);
    $exec = mysqli_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);

    if ($count == 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO cheque_name (name) VALUES (?)");
        $bing = mysqli_stmt_bind_param($stmt, "s", $cheque_name);
        $exec = mysqli_execute($stmt);
    }
}

function saveName2($cheque_name2) {
    include '../config.php';
    
    $stmt = mysqli_prepare($conn, "SELECT * FROM cheque_name WHERE name=?");
    $bind = mysqli_stmt_bind_param($stmt, "s", $cheque_name2);
    $exec = mysqli_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);

    if ($count == 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO cheque_name (name) VALUES (?)");
        $bing = mysqli_stmt_bind_param($stmt, "s", $cheque_name2);
        $exec = mysqli_execute($stmt);
    }
}

function update($user_id, $payment_id) {
    include '../config.php';
    $err = 0;
    $sql_save = mysqli_query($conn, "SELECT * FROM temp_payment WHERE user_id='$user_id'");
    $rs_save = mysqli_fetch_array($sql_save);

    $stmt = mysqli_prepare($conn, "UPDATE payment SET `bank_code`=?, `cheque_no`=?, `voucher_no`=?, `cheque_name`=?, `cheque_name2`=?, `supplier_id`=?, `description`=?, `sub_total`=?, `ts_fee`=?, `adjustments`=?, `grand_total`=?, `account_name`=?, `account_number`=?, `type`=?, `pay_type`=?, `cancelled_cheque`=?, `ap`=?, `verifier`=?, `signatory`=?, `remarks`=?, `charge_to`=?, `cheque_date`=? WHERE payment_id=?");
    $err+= checkStmt($stmt);

    $bind = mysqli_stmt_bind_param($stmt, "sssssisddddsssssssssssi", $rs_save['bank_code'], $rs_save['cheque_no'], $rs_save['voucher_no'], $rs_save['cheque_name'], $rs_save['cheque_name2'], $rs_save['supplier_id'], $rs_save['description'], $rs_save['sub_total'], $rs_save['ts_fee'], $rs_save['adjustments'], $rs_save['grand_total'], $rs_save['account_name'], $rs_save['account_number'], $rs_save['type'], $rs_save['pay_type'], $rs_save['old_cheque_no'], $rs_save['ap'], $rs_save['verifier'], $rs_save['signatory'], $rs_save['remarks'], $rs_save['charge_to'], $rs_save['cheque_date'], $payment_id);
    $exec = mysqli_execute($stmt);
    
    saveName($rs_save['cheque_name']);
    saveName2($rs_save['cheque_name2']);

    $sql_save_adj = mysqli_query($conn, "SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "'");
    while ($rs_save_adj = mysqli_fetch_array($sql_save_adj)) {
        if ($err == 0 && (!empty($rs_save_adj['particulars']) || !empty($rs_save_adj['description']))) {
            if ($rs_save_adj['others_id'] == '0') {
                $stmt = mysqli_prepare($conn, "INSERT INTO payment_others (payment_id,particulars,description,quantity,unit_price,amount) VALUES (?,?,?,?,?,?)");
                $err+= checkStmt($stmt);
                $bind = mysqli_stmt_bind_param($stmt, "issddd", $payment_id, $rs_save_adj['particulars'], $rs_save_adj['description'], $rs_save_adj['quantity'], $rs_save_adj['unit_price'], $rs_save_adj['amount']);
            } else {
                $stmt = mysqli_prepare($conn, "UPDATE payment_others SET payment_id=?,particulars=?,description=?,quantity=?,unit_price=?,amount=? WHERE id=?");
                $err+= checkStmt($stmt);
                $bind = mysqli_stmt_bind_param($stmt, "issdddi", $payment_id, $rs_save_adj['particulars'], $rs_save_adj['description'], $rs_save_adj['quantity'], $rs_save_adj['unit_price'], $rs_save_adj['amount'], $rs_save_adj['others_id']);
            }
            $exec = mysqli_execute($stmt);
        }
    }
    return $err;
}

$err = 0;

if (isset($_GET['payment']) && $_GET['payment'] == 'submitInitial') {
    $cheque_name = strtoupper($_POST['cheque_name']);
    $cheque_name2 = strtoupper($_POST['cheque_name2']);

    $temp_data = array(
        'bank_code' => $_POST['bank_code'],
        'cheque_no' => $_POST['cheque_no'],
        'old_cheque_no' => $_POST['old_cheque_no'],
        'voucher_no' => $_POST['voucher_no'],
        'cheque_name' => $cheque_name,
        'description' => strtoupper($_POST['description']),
        'supplier_id' => '',
        'sub_total' => '',
        'ts_fee' => '',
        'adjustments' => '',
        'grand_total' => round($_POST['grand_total'], 2),
        'account_name' => '',
        'account_number' => '',
        'type' => $_POST['type'],
        'pay_type' => 'Other Payment',
        'ap' => $_SESSION['user_id'],
        'verifier' => $_POST['verifier'],
        'signatory' => $_POST['signatory'],
        'ft' => $_POST['ft'],
        'remarks' => '',
        'charge_to' => '',
        'cheque_date' => $_POST['cheque_date'],
        'cheque_name2' => $cheque_name2,
        'chk_nontrade' => $_POST['chk_nontrade']
    );

    $temp_data_details = array();

    $c = 1;
    while ($c <= 20) {
        $details = array(
            'others_id' => $_POST['others_id_' . $c],
            'particulars' => strtoupper($_POST['particular_' . $c]),
            'description' => strtoupper($_POST['description_' . $c]),
            'quantity' => $_POST['quantity_' . $c],
            'unit_price' => $_POST['unit_price_' . $c],
            'amount' => $_POST['amount_' . $c],
            'user_id' => $_SESSION['user_id'],
            'others_count' => $c
        );
        array_push($temp_data_details, $details);
        unset($details);
        $c++;
    }

    $err+= saveTempPay($temp_data, $temp_data_details);
//    $err+= saveTempPatDet($temp_data_details);
}

if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinal') {
    $err+= save($_SESSION['user_id']);
}

if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalEditUpdate') {
    $err+= update($_SESSION['user_id'], $_GET['payment_id']);
}
if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalEditSave') {
    $err+= save($_SESSION['user_id'], $_GET['payment_id']);

    $err+= cancelPay($_GET['payment_id'], $_SESSION['user_id']);
}

if (isset($_GET['payment']) && $_GET['payment'] == 'getIdSaved') {
    echo maxId();
}


if ($err > 0) {
    echo "<font color='red'>Some data not save correctly in database, Please contact your system admin immediately.</font>";
} else {
    if (isset($_GET['payment']) && ($_GET['payment'] == 'submitFinal' || $_GET['payment'] == 'submitFinalEditUpdate' || $_GET['payment'] == 'submitFinalEditSave')) {
        echo "<font color='red'>Payment saved.</font>";
    }
}
?>