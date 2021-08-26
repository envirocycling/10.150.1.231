<?php

@session_start();
date_default_timezone_set("Asia/Singapore");
include '../config.php';
$err = 0;
if (isset($_GET['payment']) && $_GET['payment'] == 'submitInitial') {
    $cheque_name = mysql_real_escape_string(strtoupper(utf8_encode($_POST['cheque_name'])));
    $description = mysql_real_escape_string(strtoupper(utf8_encode($_POST['description'])));
    if (isset($_POST['old_cheque_no'])) {
        $old_cheque_no = $_POST['old_cheque_no'];
    } else {
        $old_cheque_no = "";
    }

    if (mysql_query("UPDATE temp_payment SET bank_code='" . $_POST['account'] . "', cheque_no='" . $_POST['cheque_no'] . "', old_cheque_no='$old_cheque_no', voucher_no='" . $_POST['voucher_no'] . "', cheque_name='$cheque_name', description='$description', sub_total='', ts_fee='', adjustments='', grand_total='" . $_POST['grand_total'] . "', type='" . $_POST['type'] . "', ap='" . $_SESSION['user_id'] . "', verifier='" . $_SESSION['verifier'] . "', signatory='" . $_SESSION['signatory'] . "',cheque_date='" . $_POST['cheque_date'] . "' WHERE user_id='" . $_SESSION['user_id'] . "'")) {
        $c = 1;
        while ($c <= 20) {
            $particular = mysql_real_escape_string(strtoupper(utf8_encode($_POST['particular_' . $c])));
            $description = mysql_real_escape_string(strtoupper(utf8_encode($_POST['description_' . $c])));

            if (mysql_query("UPDATE `temp_payment_others` SET `particulars` =  '$particular',`description` =  '$description',`quantity` =  '" . $_POST['quantity_' . $c] . "',`unit_price` =  '" . $_POST['unit_price_' . $c] . "',`amount` =  '" . $_POST['amount_' . $c] . "' WHERE  `user_id`='" . $_SESSION['user_id'] . "' and `others_count` =  '$c'")) {
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

if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinal') {
    $my_date = date("Y/m/d");
    $sql_id = mysql_query("SELECT max(payment_id) FROM payment");
    $rs_id = mysql_fetch_array($sql_id);
    $payment_id = $rs_id['max(payment_id)'] + 1;

    $sql_save = mysql_query("SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
    $rs_save = mysql_fetch_array($sql_save);
    $sql_check = mysql_query("SELECT * FROM payment WHERE cheque_no='" . $rs_save['cheque_no'] . "' and status!='deleted'");
    $rs_count = mysql_num_rows($sql_check);
    $rs_check = mysql_fetch_array($sql_check);
    if ($rs_count == 0) {
        $cheque_name = mysql_real_escape_string(strtoupper($rs_save['cheque_name']));
        $description = mysql_real_escape_string(strtoupper($rs_save['description']));

        $sql_check_name = mysql_query("SELECT * FROM cheque_name WHERE name='$cheque_name'");
        $rs_count_name = mysql_num_rows($sql_check_name);
        $rs_check_name = mysql_fetch_array($sql_check_name);

        if ($rs_count_name == 0) {
            mysql_query("INSERT INTO cheque_name (name) VALUES ('$cheque_name')");
        }

        if (mysql_query("INSERT INTO payment (`bank_code`, `cheque_no`, `voucher_no`, `cheque_name`, `description`,`sub_total`, `ts_fee`, `adjustments`, `grand_total`, `type`, `pay_type`, `ap`, `verifier`, `signatory`, `date`,`cheque_date`)
    VALUES ('" . $rs_save['bank_code'] . "','" . $rs_save['cheque_no'] . "','" . $rs_save['voucher_no'] . "','$cheque_name','$description','" . $rs_save['sub_total'] . "','" . $rs_save['ts_fee'] . "','" . $rs_save['adjustments'] . "','" . $rs_save['grand_total'] . "', '" . $rs_save['type'] . "', 'Other Payment', '" . $rs_save['ap'] . "', '" . $rs_save['verifier'] . "', '" . $rs_save['signatory'] . "','$my_date', '" . $rs_save['cheque_date'] . "')")) {
            $sql_save_adj = mysql_query("SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "'");
            while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
                if (!empty($rs_save_adj['particulars']) || !empty($rs_save_adj['description'])) {
                    $particular = mysql_real_escape_string(strtoupper($rs_save_adj['particulars']));
                    $description = mysql_real_escape_string(strtoupper($rs_save_adj['description']));
                    if (mysql_query("INSERT INTO payment_others (payment_id,particulars,description,quantity,unit_price,amount) VALUES ('$payment_id','$particular','$description','" . $rs_save_adj['quantity'] . "','" . $rs_save_adj['unit_price'] . "','" . $rs_save_adj['amount'] . "')")) {
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
}

//Edit

if (isset($_GET['payment']) && $_GET['payment'] == 'submitInitialEdit') {
    $cheque_name = mysql_real_escape_string(strtoupper(utf8_encode($_POST['cheque_name'])));
    $description = mysql_real_escape_string(utf8_encode(strtoupper($_POST['description'])));

    if (mysql_query("UPDATE temp_payment SET bank_code='" . $_POST['account'] . "', cheque_no='" . $_POST['cheque_no'] . "', old_cheque_no='" . $_POST['old_cheque_no'] . "', voucher_no='" . $_POST['voucher_no'] . "', cheque_name='$cheque_name', description='$description', sub_total='', ts_fee='', adjustments='', grand_total='" . $_POST['grand_total'] . "', type='" . $_POST['type'] . "', ap='" . $_SESSION['user_id'] . "', verifier='" . $_SESSION['verifier'] . "', signatory='" . $_SESSION['signatory'] . "',cheque_date='" . $_POST['cheque_date'] . "' WHERE user_id='" . $_SESSION['user_id'] . "'")) {
        $c = 1;
        while ($c <= 20) {
            $particular = mysql_real_escape_string(strtoupper(utf8_encode($_POST['particular_' . $c])));
            $description = mysql_real_escape_string(strtoupper(utf8_encode($_POST['description_' . $c])));

            if (mysql_query("UPDATE `temp_payment_others` SET `others_id`='" . $_POST['others_id_' . $c] . "',`particulars` =  '$particular',`description` =  '$description',`quantity` =  '" . $_POST['quantity_' . $c] . "',`unit_price` =  '" . $_POST['unit_price_' . $c] . "',`amount` =  '" . $_POST['amount_' . $c] . "' WHERE  `user_id`='" . $_SESSION['user_id'] . "' and `others_count` =  '$c'")) {
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

if (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalEdit') {
    $my_date = date("Y/m/d");
    $sql_id = mysql_query("SELECT max(payment_id) FROM payment");
    $rs_id = mysql_fetch_array($sql_id);
    $payment_id = $rs_id['max(payment_id)'] + 1;

    $sql_save = mysql_query("SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
    $rs_save = mysql_fetch_array($sql_save);

    $cheque_name = mysql_real_escape_string(strtoupper($rs_save['cheque_name']));
    $description = mysql_real_escape_string(strtoupper($rs_save['description']));


    $sql_check_name = mysql_query("SELECT * FROM cheque_name WHERE name='$cheque_name'");
    $rs_count_name = mysql_num_rows($sql_check_name);
    $rs_check_name = mysql_fetch_array($sql_check_name);

    if ($rs_count_name == 0) {
        mysql_query("INSERT INTO cheque_name (name) VALUES ('$cheque_name')");
    }

    $sql_check = mysql_query("SELECT * FROM payment WHERE cheque_no='" . $rs_save['cheque_no'] . "' and status!='deleted'");
    $rs_count = mysql_num_rows($sql_check);
    $rs_check = mysql_fetch_array($sql_check);
    if ($rs_count == 0) {

        if (mysql_query("INSERT INTO payment (`bank_code`, `cheque_no`, `voucher_no`, `cheque_name`, `description`, `sub_total`, `ts_fee`, `adjustments`, `grand_total`, `type`, `pay_type`, `cancelled_cheque`, `date`, `cheque_date`)
    VALUES ('" . $rs_save['bank_code'] . "','" . $rs_save['cheque_no'] . "','" . $rs_save['voucher_no'] . "','$cheque_name','$description','" . $rs_save['sub_total'] . "','" . $rs_save['ts_fee'] . "','" . $rs_save['adjustments'] . "','" . $rs_save['grand_total'] . "', '" . $rs_save['type'] . "', 'Other Payment','" . $rs_save['old_cheque_no'] . "', '" . date("Y/m/d") . "','" . $rs_save['cheque_date'] . "')")) {

            $sql_save_adj = mysql_query("SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "'");
            while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
                if (!empty($rs_save_adj['particulars']) || !empty($rs_save_adj['description'])) {
                    $particular = mysql_real_escape_string(strtoupper($rs_save_adj['particulars']));
                    $description = mysql_real_escape_string(strtoupper($rs_save_adj['description']));
                    if (mysql_query("INSERT INTO payment_others (payment_id,particulars,description,quantity,unit_price,amount) VALUES ('$payment_id','$particular','$description','" . $rs_save_adj['quantity'] . "','" . $rs_save_adj['unit_price'] . "','" . $rs_save_adj['amount'] . "')")) {
                        //do something
                    } else {
                        $err++;
                    }
                }
            }

            if (mysql_query("UPDATE payment SET status='cancelled',charge_to='" . $_SESSION['user_id'] . "' WHERE payment_id='" . $_POST['payment_id'] . "'")) {
                //do something
            } else {
                $err++;
            }
        } else {
            $err++;
        }
    } else {

        if (mysql_query("UPDATE payment SET cheque_no='" . $rs_save['cheque_no'] . "',voucher_no='" . $rs_save['voucher_no'] . "',cheque_name='$cheque_name',description='$description',sub_total='" . $rs_save['sub_total'] . "',ts_fee='" . $rs_save['ts_fee'] . "',adjustments='" . $rs_save['adjustments'] . "',grand_total='" . $rs_save['grand_total'] . "',charge_to='" . $_SESSION['user_id'] . "' WHERE payment_id='" . $_POST['payment_id'] . "'")) {
            $sql_save_adj = mysql_query("SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "'");
            while ($rs_save_adj = mysql_fetch_array($sql_save_adj)) {
                if (!empty($rs_save_adj['particulars']) || !empty($rs_save_adj['description'])) {
                    $particular = mysql_real_escape_string(strtoupper($rs_save_adj['particulars']));
                    $description = mysql_real_escape_string(strtoupper($rs_save_adj['description']));

                    if ($rs_save_adj['others_id'] == '') {
                        if (mysql_query("INSERT INTO payment_others (payment_id,particulars,description,quantity,unit_price,amount) VALUES ('" . $_POST['payment_id'] . "','$particular','$description','" . $rs_save_adj['quantity'] . "','" . $rs_save_adj['unit_price'] . "','" . $rs_save_adj['amount'] . "')")) {
                            //do something
                        } else {
                            $err++;
                        }
                    } else {
                        if (mysql_query("UPDATE payment_others SET particulars='$particular',description='$description',quantity='" . $rs_save_adj['quantity'] . "',unit_price='" . $rs_save_adj['unit_price'] . "',amount='" . $rs_save_adj['amount'] . "' WHERE id='" . $rs_save_adj['others_id'] . "'")) {
                            //do something
                        } else {
                            $err++;
                        }
                    }
                }

                if (!empty($rs_save_adj['others_id']) && empty($rs_save_adj['particulars']) && empty($rs_save_adj['description'])) {
                    mysql_query("DELETE FROM payment_others WHERE id='" . $rs_save_adj['others_id'] . "'");
                }
            }
        } else {
            $err++;
        }
    }
}


if ($err > 0) {
    echo "<font color='red'>Some data not save correctly in database, Please contact your system admin immediately.</font>";
} else {
    if ((isset($_GET['payment']) && $_GET['payment'] == 'submitFinal') || (isset($_GET['payment']) && $_GET['payment'] == 'submitFinalEdit')) {
        echo "<font color='red'>Payment saved.</font>";
    }
}
?>
