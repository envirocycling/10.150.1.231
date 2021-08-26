<?php

@session_start();
date_default_timezone_set("Asia/Singapore");
include '../config.php';
$my_date = date("Y/m/d");
$my_time = date("H:i");
$err = 0;

function updateBr($ac_id) {
    include '../config.php';
    $sql_adv = mysql_query("SELECT branch_id FROM adv WHERE ac_id='$ac_id'");
    $rs_adv = mysql_fetch_array($sql_adv);

    if ($rs_adv['branch_id'] != '7') {
        mysql_query("UPDATE adv SET upt_br='0' WHERE ac_id='$ac_id'");
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'submitForm') {
    $ac_no = $_POST['ac_no'];
    $supplier_id = $_POST['supplier_id'];
    $amount = $_POST['amount'];
    $acpty_id = $_POST['acpty_id'];
    $acty_id = $_POST['acty_id'];
    $justification = mysql_real_escape_string(strtoupper(utf8_encode($_POST['justification'])));
    $terms = mysql_real_escape_string(strtoupper(utf8_encode($_POST['terms'])));
    $user_id = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    $prepaid = $_POST['prepaid'];

    $sql_ac_no = mysql_query("SELECT * FROM adv_sysgen_no");
    $rs_ac_no = mysql_fetch_array($sql_ac_no);
    $no = $rs_ac_no['sup_nx_ctrl_no'];

    $new_no = $no + 1;

    if (mysql_query("INSERT INTO `adv`(`ac_no`, `supplier_id`, `branch_id`, `amount`, `acpty_id`, `acty_id`, `justification`, `terms`,`prepaid`,`user_id`, `date`)
        VALUES ('$ac_no','$supplier_id','7','$amount','$acpty_id','$acty_id','$justification','$terms','$prepaid','$user_id','$date')")) {
        if (mysql_query("UPDATE adv_sysgen_no SET sup_nx_ctrl_no='$new_no'")) {
            //do something
        } else {
            $err++;
        }
    } else {
        $err++;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'submitEditForm') {
    $ac_id = $_GET['ac_id'];
    $ac_no = $_POST['ac_no'];
    $supplier_id = $_POST['supplier_id'];
    $amount = $_POST['amount'];
    $acpty_id = $_POST['acpty_id'];
    $acty_id = $_POST['acty_id'];
    $justification = mysql_real_escape_string(strtoupper(utf8_encode($_POST['justification'])));
    $terms = mysql_real_escape_string(strtoupper(utf8_encode($_POST['terms'])));
    $user_id = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    $prepaid = $_POST['prepaid'];

    $sql_ac_no = mysql_query("SELECT * FROM adv_sysgen_no");
    $rs_ac_no = mysql_fetch_array($sql_ac_no);
    $no = $rs_ac_no['sup_nx_ctrl_no'];

    $new_no = $no + 1;

    if (mysql_query("UPDATE `adv` SET `ac_no`='$ac_no',`supplier_id`='$supplier_id',`amount`='$amount',`acpty_id`='$acpty_id',`acty_id`='$acty_id',`justification`='$justification',`terms`='$terms',prepaid='$prepaid' WHERE ac_id='$ac_id'")) {
        //do something
    } else {
        $err++;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'processCash') {
    $ac_id = $_POST['ac_id'];
    $date = date("Y-m-d H:i:s");
    mysql_query("UPDATE adv SET status='issued',date_processed='$date' WHERE ac_id='$ac_id'");
}

if (isset($_GET['action']) && $_GET['action'] == 'cancelAc') {
    $ac_id = $_POST['ac_id'];
    mysql_query("UPDATE adv SET status='cancelled' WHERE ac_id='$ac_id'");
}

if (isset($_GET['payment']) && $_GET['payment'] == 'submitInitial') {
    $cheque_name = mysql_real_escape_string(strtoupper(utf8_encode($_POST['cheque_name'])));
    if (mysql_query("UPDATE temp_payment SET bank_code='" . $_POST['bank_code'] . "', cheque_no='" . $_POST['cheque_no'] . "', old_cheque_no='" . $_POST['old_cheque_no'] . "', voucher_no='" . $_POST['voucher_no'] . "', cheque_name='$cheque_name', sub_total='', ts_fee='', adjustments='', grand_total='" . $_POST['grand_total'] . "', type='" . $_POST['type'] . "', ap='" . $_SESSION['user_id'] . "', verifier='" . $_SESSION['trade_verifier'] . "', signatory='" . $_SESSION['trade_signatory'] . "',cheque_date='" . $_POST['cheque_date'] . "' WHERE user_id='" . $_SESSION['user_id'] . "'")) {

        $particular = mysql_real_escape_string(strtoupper(utf8_encode($_POST['particular'])));
        if (mysql_query("UPDATE `temp_payment_others` SET `others_id` =  '" . $_POST['others_id'] . "',`particulars` =  '$particular',`description`='$particular',`quantity` =  '" . $_POST['quantity'] . "',`unit_price` =  '" . $_POST['unit_price'] . "',`amount` =  '" . $_POST['amount'] . "' WHERE  `user_id`='" . $_SESSION['user_id'] . "' and `others_count` =  '1'")) {
            //do something
        } else {
            $err++;
        }
    } else {
        $err++;
    }
}

if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinal') {

    $sql_id = mysql_query("SELECT max(payment_id) FROM payment");
    $rs_id = mysql_fetch_array($sql_id);
    $payment_id = $rs_id['max(payment_id)'] + 1;

    $sql_save = mysql_query("SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
    $rs_save = mysql_fetch_array($sql_save);
    $sql_check = mysql_query("SELECT * FROM payment WHERE cheque_no='" . $rs_save['cheque_no'] . "' and status!='deleted' and bank_code='".$rs_save['bank_code']."'");
    $rs_count = mysql_num_rows($sql_check);
    $rs_check = mysql_fetch_array($sql_check);
    if ($rs_count == 0) {
        $cheque_name = mysql_real_escape_string(strtoupper($rs_save['cheque_name']));

        $sql_check_name = mysql_query("SELECT * FROM cheque_name WHERE name='$cheque_name'");
        $rs_count_name = mysql_num_rows($sql_check_name);
        $rs_check_name = mysql_fetch_array($sql_check_name);

        if ($rs_count_name == 0) {
            mysql_query("INSERT INTO cheque_name (supplier_id, name) VALUES ('" . $_POST['supplier_id'] . "','$cheque_name')");
        }

        if (mysql_query("INSERT INTO payment (`bank_code`, `cheque_no`, `voucher_no`, `cheque_name`, `supplier_id`, `sub_total`, `ts_fee`, `adjustments`, `grand_total`, `type`, `pay_type`, `ap`, `verifier`, `signatory`, `date`,`time`,`cheque_date`)
    VALUES ('" . $rs_save['bank_code'] . "','" . $rs_save['cheque_no'] . "','" . $rs_save['voucher_no'] . "','$cheque_name','" . $_POST['supplier_id'] . "','" . $rs_save['sub_total'] . "','" . $rs_save['ts_fee'] . "','" . $rs_save['adjustments'] . "','" . $rs_save['grand_total'] . "', '" . $rs_save['type'] . "', 'Advances', '" . $rs_save['ap'] . "', '" . $rs_save['verifier'] . "', '" . $rs_save['signatory'] . "','$my_date','$my_time','" . $rs_save['cheque_date'] . "')")) {
            $sql_save_adj = mysql_query("SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "'");
            while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
                if (!empty($rs_save_adj['particulars'])) {
                    $particular = mysql_real_escape_string(strtoupper($rs_save_adj['particulars']));
                    if (mysql_query("INSERT INTO payment_others (payment_id,particulars,description,quantity,unit_price,amount) VALUES ('$payment_id','$particular','$particular','" . $rs_save_adj['quantity'] . "','" . $rs_save_adj['unit_price'] . "','" . $rs_save_adj['amount'] . "')")) {
                        //do something
                    } else {
                        $err++;
                    }
                }
            }
        } else {

            $err++;
        }
    }

    $date = date("Y-m-d H:i:s");
    mysql_query("UPDATE adv SET payment_id='$payment_id',status='issued',date_processed='$date' WHERE ac_id='" . $_GET['ac_id'] . "'");
    updateBr($_GET['ac_id']);
}

if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalEdit') {
    $sql_id = mysql_query("SELECT max(payment_id) FROM payment");
    $rs_id = mysql_fetch_array($sql_id);
    $payment_id = $rs_id['max(payment_id)'] + 1;

    $sql_save = mysql_query("SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
    $rs_save = mysql_fetch_array($sql_save);

    $cheque_name = mysql_real_escape_string(strtoupper($rs_save['cheque_name']));

    $sql_check_name = mysql_query("SELECT * FROM cheque_name WHERE name='$cheque_name'");
    $rs_count_name = mysql_num_rows($sql_check_name);
    $rs_check_name = mysql_fetch_array($sql_check_name);

    if ($rs_count_name == 0) {
        mysql_query("INSERT INTO cheque_name (supplier_id, name) VALUES ('" . $_POST['supplier_id'] . "','$cheque_name')");
    }

    $sql_check = mysql_query("SELECT * FROM payment WHERE cheque_no='" . $rs_save['cheque_no'] . "' and status!='deleted'");
    $rs_count = mysql_num_rows($sql_check);
    $rs_check = mysql_fetch_array($sql_check);
    if ($rs_count == 0) {

        if (mysql_query("INSERT INTO payment (`bank_code`, `cheque_no`, `voucher_no`, `cheque_name`, `supplier_id`, `sub_total`, `ts_fee`, `adjustments`, `grand_total`, `type`, `pay_type`, `cancelled_cheque`, `date`, `time`, `cheque_date`)
    VALUES ('" . $rs_save['bank_code'] . "','" . $rs_save['cheque_no'] . "','" . $rs_save['voucher_no'] . "','$cheque_name','" . $_POST['supplier_id'] . "','" . $rs_save['sub_total'] . "','" . $rs_save['ts_fee'] . "','" . $rs_save['adjustments'] . "','" . $rs_save['grand_total'] . "', '" . $rs_save['type'] . "', 'Advances','" . $rs_save['old_cheque_no'] . "', '$my_date','$my_time','" . $rs_save['cheque_date'] . "')")) {

            $sql_save_adj = mysql_query("SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "'");
            while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
                if (!empty($rs_save_adj['particulars'])) {
                    $particular = mysql_real_escape_string(strtoupper($rs_save_adj['particulars']));
                    if (mysql_query("INSERT INTO payment_others (payment_id,particulars,description,quantity,unit_price,amount) VALUES ('$payment_id','$particular','$particular','" . $rs_save_adj['quantity'] . "','" . $rs_save_adj['unit_price'] . "','" . $rs_save_adj['amount'] . "')")) {
                        //do something
                    } else {
                        $err++;
                    }
                }
            }
            mysql_query("UPDATE payment SET status='cancelled',charge_to='" . $_SESSION['user_id'] . "' WHERE payment_id='" . $_POST['payment_id'] . "'");

            $date = date("Y-m-d H:i:s");
            mysql_query("UPDATE adv SET payment_id='$payment_id',status='issued',date_processed='$date' WHERE ac_id='" . $_GET['ac_id'] . "'");
            updateBr($_GET['ac_id']);
        } else {
            $err++;
        }
    } else {

        if (mysql_query("UPDATE payment SET cheque_no='" . $rs_save['cheque_no'] . "',voucher_no='" . $rs_save['voucher_no'] . "',cheque_name='$cheque_name',sub_total='" . $rs_save['sub_total'] . "',ts_fee='" . $rs_save['ts_fee'] . "',adjustments='" . $rs_save['adjustments'] . "',grand_total='" . $rs_save['grand_total'] . "',charge_to='" . $_SESSION['user_id'] . "' WHERE payment_id='" . $_POST['payment_id'] . "'")) {

            $sql_save_adj = mysql_query("SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "'");
            while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
                if (!empty($rs_save_adj['particulars'])) {
                    $particular = mysql_real_escape_string(strtoupper($rs_save_adj['particulars']));

                    if ($rs_save_adj['others_id'] == '') {
                        if (mysql_query("INSERT INTO payment_others (payment_id,particulars,description,quantity,unit_price,amount) VALUES ('" . $_POST['payment_id'] . "','$particular','$particular','" . $rs_save_adj['quantity'] . "','" . $rs_save_adj['unit_price'] . "','" . $rs_save_adj['amount'] . "')")) {
                            //do something
                        } else {
                            $err++;
                        }
                    } else {
                        if (mysql_query("UPDATE payment_others SET particulars='$particular',description='$particular',quantity='" . $rs_save_adj['quantity'] . "',unit_price='" . $rs_save_adj['unit_price'] . "',amount='" . $rs_save_adj['amount'] . "' WHERE id='" . $rs_save_adj['others_id'] . "'")) {
                            //do something
                        } else {
                            $err++;
                        }
                    }
                }
            }
        }
    }
}


if (isset($_GET['payment']) && $_GET['payment'] == 'submitDigiInitial') {
    $cheque_name = mysql_real_escape_string(strtoupper(utf8_encode($_POST['cheque_name'])));
    if (mysql_query("UPDATE temp_payment SET bank_code='" . $_POST['account'] . "', cheque_no='SBC - Digibanker', voucher_no='" . $_POST['voucher_no'] . "', cheque_name='$cheque_name', sub_total='', ts_fee='', adjustments='', grand_total='" . $_POST['grand_total'] . "', account_name='$cheque_name', account_number='" . $_POST['account_number'] . "',type='" . $_POST['type'] . "', ap='" . $_SESSION['user_id'] . "', verifier='" . $_SESSION['trade_verifier'] . "', signatory='" . $_SESSION['trade_signatory'] . "',cheque_date='" . $_POST['cheque_date'] . "' WHERE user_id='" . $_SESSION['user_id'] . "'")) {
        $particular = mysql_real_escape_string(strtoupper(utf8_encode($_POST['particular'])));

        if (mysql_query("UPDATE `temp_payment_others` SET `particulars` =  '$particular',`description` =  '$particular',`quantity` =  '" . $_POST['quantity'] . "',`unit_price` =  '" . $_POST['unit_price'] . "',`amount` =  '" . $_POST['amount'] . "' WHERE  `user_id`='" . $_SESSION['user_id'] . "' and `others_count` =  '1'")) {
            //do something
        } else {
            $err++;
        }
    } else {
        $err++;
    }
}

if (isset($_GET['payment']) && $_GET['payment'] == 'submitDigiFinal') {
    $sql_id = mysql_query("SELECT max(payment_id) FROM payment");
    $rs_id = mysql_fetch_array($sql_id);
    $payment_id = $rs_id['max(payment_id)'] + 1;

    $sql_save = mysql_query("SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
    $rs_save = mysql_fetch_array($sql_save);
    $sql_check = mysql_query("SELECT * FROM payment WHERE voucher_no='" . $rs_save['voucher_no'] . "'");
    $rs_count = mysql_num_rows($sql_check);
    $rs_check = mysql_fetch_array($sql_check);
    if ($rs_count == 0) {
        $cheque_name = mysql_real_escape_string(strtoupper($rs_save['cheque_name']));

        $sql_check_name = mysql_query("SELECT * FROM sup_bank_accounts WHERE account_name='$cheque_name' and supplier_id='" . $_POST['supplier_id'] . "'");
        $rs_count_name = mysql_num_rows($sql_check_name);
        $rs_check_name = mysql_fetch_array($sql_check_name);

        if ($rs_count_name == 0) {
            mysql_query("INSERT INTO sup_bank_accounts (supplier_id, account_name, account_number)
                VALUES ('" . $_POST['supplier_id'] . "','$cheque_name','" . $rs_save['account_number'] . "')");
        }

        if (mysql_query("INSERT INTO payment (`bank_code`, `cheque_no`, `voucher_no`, `cheque_name`, `supplier_id`, `sub_total`, `ts_fee`, `adjustments`, `grand_total`, `account_name`, `account_number`, `type`, `pay_type`, `ap`, `verifier`, `signatory`, `date`,`time`,`cheque_date`)
    VALUES ('" . $rs_save['bank_code'] . "','" . $rs_save['cheque_no'] . "','" . $rs_save['voucher_no'] . "','$cheque_name','" . $_POST['supplier_id'] . "','" . $rs_save['sub_total'] . "','" . $rs_save['ts_fee'] . "','" . $rs_save['adjustments'] . "','" . $rs_save['grand_total'] . "','" . $rs_save['account_name'] . "','" . $rs_save['account_number'] . "', '" . $rs_save['type'] . "', 'Advances', '" . $rs_save['ap'] . "', '" . $rs_save['verifier'] . "', '" . $rs_save['signatory'] . "','$my_date', '$my_time','" . $rs_save['cheque_date'] . "')")) {
            $sql_save_adj = mysql_query("SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "'");
            while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
                if (!empty($rs_save_adj['particulars'])) {
                    $particular = mysql_real_escape_string(strtoupper($rs_save_adj['particulars']));
                    if (mysql_query("INSERT INTO payment_others (payment_id,particulars,description,quantity,unit_price,amount) VALUES ('$payment_id','$particular','$particular','" . $rs_save_adj['quantity'] . "','" . $rs_save_adj['unit_price'] . "','" . $rs_save_adj['amount'] . "')")) {
                        //do something
                    } else {
                        $err++;
                    }
                }
            }
        } else {
            $err++;
        }
        // im herre

        $date = date("Y-m-d H:i:s");
        mysql_query("UPDATE adv SET payment_id='$payment_id',status='issued',date_processed='$date' WHERE ac_id='" . $_GET['ac_id'] . "'");
        updateBr($_GET['ac_id']);
    }
}

if (isset($_GET['payment']) && $_GET['payment'] == 'payCash') {
    $remarks = mysql_real_escape_string(strtoupper(utf8_encode($_POST['remarks'])));
    mysql_query("INSERT INTO `adv_payment`(`ac_id`, `amount`, `remarks`, `user_id`, `paid_date`)
            VALUES ('" . $_GET['ac_id'] . "','" . $_POST['amount'] . "','$remarks','" . $_SESSION['user_id'] . "','" . date("Y-m-d H:i:s") . "')");


    $ac_id = $_GET['ac_id'];

    $sql_adv = mysql_query("SELECT * FROM adv WHERE ac_id='$ac_id'");
    $rs_count = mysql_num_rows($sql_adv);
    $rs_adv = mysql_fetch_array($sql_adv);

    if ($rs_count > 0) {
        $sql_adv_less = mysql_query("SELECT sum(amount) FROM payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='$ac_id' and payment.status!='cancelled'");
        $rs_adv_less = mysql_fetch_array($sql_adv_less);

        $sql_adv_pay = mysql_query("SELECT sum(amount) FROM adv_payment WHERE ac_id='$ac_id' and status!='cancelled'");
        $rs_adv_pay = mysql_fetch_array($sql_adv_pay);

        $total_less = $rs_adv_pay['sum(amount)'] + $rs_adv_less['sum(amount)'];

        $total = $rs_adv['amount'] - $total_less;

        $date_time = date("Y-m-d H:i:s");

        if ($total == 0) {
            mysql_query("UPDATE adv SET status='paid', date_paid='$date_time' WHERE ac_id='$ac_id'");
        }
    }
}

if (isset($_GET['payment']) && $_GET['payment'] == 'getIdSaved') {
    $sql_max_id = mysql_query("SELECT max(payment_id) FROM payment WHERE status!='deleted'");
    $rs_max_id = mysql_fetch_array($sql_max_id);

    echo $rs_max_id['max(payment_id)'];
}

if ($err > 0) {
    echo "<font color='red'>Some data not save correctly in database, Please contact your system admin immediately.</font>";
} else {
    if ((isset($_GET['payment']) && $_GET['payment'] == 'submitFinal') || (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalEdit') || (isset($_GET['payment']) && $_GET['payment'] == 'submitDigiFinal')) {
        echo "<font color='red'>Payment saved.</font>";
    }
}
?>