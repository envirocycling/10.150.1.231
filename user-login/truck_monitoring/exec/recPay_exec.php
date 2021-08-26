<?php

@session_start();
date_default_timezone_set("Asia/Singapore");
include '../config.php';
$err = 0;

function checkIfPaid($payment_id, $ac_id, $type) {
    $date_time = date("Y-m-d H:i:s");
    if ($type == 'add') {
        mysql_query("UPDATE adv SET payment_id='$payment_id', status='issued', date_processed='$date_time' WHERE ac_id='$ac_id'");
    } else {
        $sql_adv = mysql_query("SELECT * FROM adv WHERE ac_id='$ac_id'");
        $rs_count = mysql_num_rows($sql_adv);
        $rs_adv = mysql_fetch_array($sql_adv);
        if ($rs_count > 0) {
            $sql_adv_less = mysql_query("SELECT sum(amount) FROM payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='$ac_id' and payment_adjustment.adj_type='deduct' and payment.status!='cancelled' and payment.status!='deleted'");
            $rs_adv_less = mysql_fetch_array($sql_adv_less);

            $sql_adv_pay = mysql_query("SELECT sum(amount) FROM adv_payment WHERE ac_id='$ac_id' and status!='cancelled'");
            $rs_adv_pay = mysql_fetch_array($sql_adv_pay);

            $total_less = $rs_adv_pay['sum(amount)'] + $rs_adv_less['sum(amount)'];

            $total = $rs_adv['amount'] - $total_less;
            if ($total == 0) {
                mysql_query("UPDATE adv SET status='paid', date_paid='$date_time' WHERE ac_id='$ac_id'");
            } else {
                mysql_query("UPDATE adv SET status='issued' WHERE ac_id='$ac_id'");
            }
        }
    }
}

function checkAdj($adj_id) {
    $sql_adj = mysql_query("SELECT * FROM payment_adjustment WHERE adj_id='$adj_id'");
    $rs_adj = mysql_fetch_array($sql_adj);
    if ($rs_adj['adj_type'] == 'add') {
        mysql_query("UPDATE adv SET status='approved' WHERE ac_id='" . $rs_adj['ac_id'] . "'");
    } else {
        checkIfPaid($rs_adj['payment_id'], $rs_adj['ac_id'], 'deduct');
    }
}

function checkAdjDel($adj_id) {
    $sql_adj = mysql_query("SELECT * FROM payment_adjustment WHERE adj_id='$adj_id'");
    $rs_adj = mysql_fetch_array($sql_adj);
    if ($rs_adj['adj_type'] == 'add') {
        mysql_query("UPDATE adv SET status='approved' WHERE ac_id='" . $rs_adj['ac_id'] . "'");
        mysql_query("DELETE FROM payment_adjustment WHERE adj_id='$adj_id'");
    } else {
        mysql_query("DELETE FROM payment_adjustment WHERE adj_id='$adj_id'");
        checkIfPaid($rs_adj['payment_id'], $rs_adj['ac_id'], 'deduct');
    }
}

//Initial encoding adjustment
if (isset($_GET['payment']) && $_GET['payment'] == 'submitInitial') {

    if (isset($_POST['remarks']) && $_POST['remarks'] != 'undefined') {
        $remarks = $_POST['remarks'];
    } else {
        $remarks = "";
    }

    if (mysql_query("UPDATE temp_payment SET sub_total='" . $_POST['sub_total'] . "', adjustments='" . $_POST['adjustment'] . "', grand_total='" . $_POST['grand_total'] . "', remarks='$remarks' WHERE user_id='" . $_SESSION['user_id'] . "'")) {
        $c = 1;
        while ($c <= 8) {
            $description = mysql_real_escape_string(strtoupper(utf8_encode($_POST['desc_' . $c])));

            if (isset($_POST['adj_id_' . $c])) {
                $adj = $_POST['adj_id_' . $c];
            } else {
                $adj = "";
            }

            if (mysql_query("UPDATE `temp_payment_adjustment` SET `adj_id`='$adj',`ac_id`='" . $_POST['ac_id_' . $c] . "', `adj_type` =  '" . $_POST['adj_' . $c] . "',`desc` =  '$description',`amount` =  '" . $_POST['amount_' . $c] . "' WHERE `user_id`='" . $_SESSION['user_id'] . "' and `adj_count` =  '$c'")) {
                //do something
            } else {
                $err++;
            }
            $c++;
        }
    } else {
        $err++;
    }
}

//Final encoding cheque name
if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinal') {
    $cheque_name = mysql_real_escape_string(strtoupper(utf8_encode($_POST['cheque_name'])));

    if (isset($_POST['account_num'])) {
        $account_number = $_POST['account_num'];
    } else {
        $account_number = "";
    }
    
    if (mysql_query("UPDATE temp_payment SET bank_code='" . $_POST['account'] . "', cheque_no='" . $_POST['cheque_no'] . "', voucher_no='" . $_POST['voucher_no'] . "', cheque_name='$cheque_name', supplier_id='" . $_POST['supplier_id'] . "', account_name='$cheque_name', account_number='$account_number', ap='" . $_SESSION['user_id'] . "', verifier='" . $_POST['verifier'] . "', signatory='" . $_POST['signatory'] . "' WHERE user_id='" . $_SESSION['user_id'] . "'")) {
        // do something
    } else {
        $err++;
    }
}
// Print Cheque
if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalCheque') {
    $sql_id = mysql_query("SELECT max(payment_id) FROM payment");
    $rs_id = mysql_fetch_array($sql_id);
    $payment_id = $rs_id['max(payment_id)'] + 1;

    $sql_save = mysql_query("SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
    $rs_save = mysql_fetch_array($sql_save);
    $sql_check = mysql_query("SELECT * FROM payment WHERE cheque_no='" . $rs_save['cheque_no'] . "' and voucher_no='" . $rs_save['voucher_no'] . "' and date='" . date("Y/m/d") . "' and status!='deleted'");
    $rs_count = mysql_num_rows($sql_check);
    $rs_check = mysql_fetch_array($sql_check);

    if ($rs_count == 0) {

        $cheque_name = mysql_real_escape_string(strtoupper($rs_save['cheque_name']));

        if ($cheque_name != '') {
            if ($_POST['payment'] == 'cheque') {
                $sql_check_name = mysql_query("SELECT * FROM cheque_name WHERE name='$cheque_name' and supplier_id='" . $rs_save['supplier_id'] . "'");
                $rs_count_name = mysql_num_rows($sql_check_name);
                $rs_check_name = mysql_fetch_array($sql_check_name);

                if ($rs_count_name == 0) {
                    mysql_query("INSERT INTO cheque_name (supplier_id, name) VALUES ('" . $rs_save['supplier_id'] . "','$cheque_name')");
                }
            } else {
                $sql_check_name = mysql_query("SELECT * FROM sup_bank_accounts WHERE account_name='$cheque_name' and supplier_id='" . $rs_save['supplier_id'] . "'");
                $rs_count_name = mysql_num_rows($sql_check_name);
                $rs_check_name = mysql_fetch_array($sql_check_name);

                if ($rs_count_name == 0) {
                    mysql_query("INSERT INTO sup_bank_accounts (supplier_id, account_name, account_number)
VALUES ('" . $rs_save['supplier_id'] . "','$cheque_name','" . $rs_save['account_number'] . "')");
                }
            }

            if (mysql_query("INSERT INTO payment (`bank_code`, `cheque_no`, `voucher_no`, `cheque_name`, `supplier_id`, `sub_total`, `adjustments`, `grand_total`, `account_name`, `account_number`, `type`, `pay_type`, `ap`, `verifier`, `signatory`, `date`,`cheque_date`)
VALUES ('" . $rs_save['bank_code'] . "','" . $rs_save['cheque_no'] . "','" . $rs_save['voucher_no'] . "','$cheque_name','" . $rs_save['supplier_id'] . "','" . $rs_save['sub_total'] . "','" . $rs_save['adjustments'] . "','" . $rs_save['grand_total'] . "', '$cheque_name','" . $rs_save['account_number'] . "', 'supplier', 'Receiving', '" . $rs_save['ap'] . "', '" . $rs_save['verifier'] . "', '" . $rs_save['signatory'] . "', '" . date("Y/m/d") . "', '" . date("Y/m/d") . "')")) {

                $sql_save_adj = mysql_query("SELECT * FROM temp_payment_adjustment WHERE user_id='" . $_SESSION['user_id'] . "'");
                while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
                    if (!empty($rs_save_adj['adj_type'])) {
                        $description = mysql_real_escape_string(strtoupper($rs_save_adj['desc']));
                        if (mysql_query("INSERT INTO `payment_adjustment` (`payment_id`, `ac_id`, `adj_type`, `desc`, `amount`)
                            VALUES ('$payment_id','" . $rs_save_adj['ac_id'] . "','" . $rs_save_adj['adj_type'] . "','$description','" . $rs_save_adj['amount'] . "')")) {
                            checkIfPaid($payment_id, $rs_save_adj['ac_id'], $rs_save_adj['adj_type']);
                        } else {
                            $err++;
                        }
                    }
                }

                $trans_array = preg_split("[_]", $_POST['tras_array']);
                foreach ($trans_array as $trans_id) {
                    if (mysql_query("UPDATE scale_receiving SET payment_id='$payment_id',voucher_no='" . $rs_save['voucher_no'] . "', cheque_no='" . $rs_save['cheque_no'] . "', status='paid', date_paid='" . date("Y/m/d") . "' WHERE trans_id='$trans_id'")) {
                        //do something
                    } else {
                        $err++;
                    }
                }
            } else {
                $err++;
            }
        } else {
            $err++;
        }
    } else {
        $err++;
    }
}

// Print Cheque Edit
if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalChequeEdit') {
    $sql_id = mysql_query("SELECT max(payment_id) FROM payment");
    $rs_id = mysql_fetch_array($sql_id);
    $payment_id = $rs_id['max(payment_id)'] + 1;

    $sql_save = mysql_query("SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
    $rs_save = mysql_fetch_array($sql_save);

    $cheque_name = mysql_real_escape_string(strtoupper($rs_save['cheque_name']));

    $sql_check_name = mysql_query("SELECT * FROM cheque_name WHERE name='$cheque_name' and supplier_id='" . $rs_save['supplier_id'] . "'");
    $rs_count_name = mysql_num_rows($sql_check_name);
    $rs_check_name = mysql_fetch_array($sql_check_name);

    if ($rs_count_name == 0) {
        mysql_query("INSERT INTO cheque_name (supplier_id, name) VALUES ('" . $rs_save['supplier_id'] . "','$cheque_name')");
    }

    $sql_check = mysql_query("SELECT * FROM payment WHERE cheque_no='" . $rs_save['cheque_no'] . "' and status!='deleted'");
    $rs_count = mysql_num_rows($sql_check);
    $rs_check = mysql_fetch_array($sql_check);

    if ($cheque_name != '') {
        if ($rs_count == 0) {

            if (mysql_query("UPDATE payment SET status='cancelled', remarks='" . $rs_save['remarks'] . "' WHERE payment_id='" . $_POST['payment_id'] . "'")) {
                //do something
            } else {
                $err++;
            }

            if (mysql_query("INSERT INTO payment (`bank_code`, `cheque_no`, `voucher_no`, `cheque_name`, `supplier_id`, `sub_total`, `ts_fee`, `adjustments`, `grand_total`, `type`, `pay_type`, `cancelled_cheque`, `ap`, `verifier`, `signatory`, `date`)
    VALUES ('" . $rs_save['bank_code'] . "','" . $rs_save['cheque_no'] . "','" . $rs_save['voucher_no'] . "','$cheque_name','" . $rs_save['supplier_id'] . "','" . $rs_save['sub_total'] . "','" . $rs_save['ts_fee'] . "','" . $rs_save['adjustments'] . "','" . $rs_save['grand_total'] . "', 'supplier', 'Receiving', '" . $rs_save['old_cheque_no'] . "','" . $rs_save['ap'] . "','" . $rs_save['verifier'] . "','" . $rs_save['signatory'] . "', '" . date("Y/m/d") . "')")) {

                $sql_save_adj = mysql_query("SELECT * FROM temp_payment_adjustment WHERE user_id='" . $_SESSION['user_id'] . "'");

                while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
                    if (!empty($rs_save_adj['adj_type'])) {
                        $description = mysql_real_escape_string(strtoupper($rs_save_adj['desc']));
                        if (mysql_query("INSERT INTO `payment_adjustment` (`payment_id`, `ac_id`, `adj_type`, `desc`, `amount`) VALUES ('$payment_id','" . $rs_save_adj['ac_id'] . "','" . $rs_save_adj['adj_type'] . "','$description','" . $rs_save_adj['amount'] . "')")) {
//                            mysql_query("DELETE FROM `payment_adjustment` WHERE `adj_id`='" . $rs_save_adj['adj_id'] . "'");

                            checkIfPaid($payment_id, $rs_save_adj['ac_id'], $rs_save_adj['adj_type']);
                        } else {
                            $err++;
                        }
                    }

                    if (!empty($rs_save_adj['adj_id']) && empty($rs_save_adj['adj_type'])) {
                        checkAdj($rs_save_adj['adj_id']);
                    }
                }

                $trans_array = preg_split("[_]", $_POST['tras_array']);
                foreach ($trans_array as $trans_id) {
                    if (mysql_query("UPDATE scale_receiving SET payment_id='$payment_id',voucher_no='" . $rs_save['voucher_no'] . "', cheque_no='" . $rs_save['cheque_no'] . "', old_cheque_no='" . $rs_save['old_cheque_no'] . "', status='paid', date_paid='" . date("Y/m/d") . "' WHERE trans_id='$trans_id'")) {
                        //do something
                    } else {
                        $err++;
                    }
                }
            } else {
                $err++;
            }
        } else {
            if (mysql_query("UPDATE payment SET cheque_no='" . $rs_save['cheque_no'] . "',voucher_no='" . $rs_save['voucher_no'] . "',cheque_name='$cheque_name',sub_total='" . $rs_save['sub_total'] . "',ts_fee='" . $rs_save['ts_fee'] . "',adjustments='" . $rs_save['adjustments'] . "',grand_total='" . $rs_save['grand_total'] . "',remarks='" . $rs_save['remarks'] . "' WHERE payment_id='" . $_POST['payment_id'] . "'")) {
                $sql_save_adj = mysql_query("SELECT * FROM temp_payment_adjustment WHERE user_id='" . $_SESSION['user_id'] . "'");

                while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
                    if (!empty($rs_save_adj['adj_type'])) {
                        $description = mysql_real_escape_string(strtoupper($rs_save_adj['desc']));

                        if ($rs_save_adj['adj_id'] == '') {
                            if (mysql_query("INSERT INTO `payment_adjustment` (`payment_id`, `ac_id`, `adj_type`, `desc`, `amount`) VALUES ('" . $_POST['payment_id'] . "','" . $rs_save_adj['ac_id'] . "','" . $rs_save_adj['adj_type'] . "','$description','" . $rs_save_adj['amount'] . "')")) {
                                checkIfPaid($_POST['payment_id'], $rs_save_adj['ac_id'], $rs_save_adj['adj_type']);
                            } else {
                                $err++;
                            }
                        } else {
                            if (mysql_query("UPDATE payment_adjustment SET ac_id='" . $rs_save_adj['ac_id'] . "',adj_type='" . $rs_save_adj['adj_type'] . "',`desc`='$description',amount='" . $rs_save_adj['amount'] . "' WHERE adj_id='" . $rs_save_adj['adj_id'] . "'")) {
                                checkIfPaid($_POST['payment_id'], $rs_save_adj['ac_id'], $rs_save_adj['adj_type']);
                            } else {
                                $err++;
                            }
                        }
                    }
                    if (!empty($rs_save_adj['adj_id']) && empty($rs_save_adj['adj_type'])) {
                        checkAdjDel($rs_save_adj['adj_id']);
                    }
                }
            } else {
                $err++;
            }
        }
    } else {
        $err++;
    }
}


if ($err > 0) {
    echo "<font color='red'>Some data not save correctly in database, Please contact your system admin immediately.</font>";
} else {
    if ((isset($_GET['payment']) && $_GET['payment'] == 'submitFinalCheque') || (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalChequeEdit')) {
        echo "<font color='red'>Payment saved.</font>";
    }
}
?>


