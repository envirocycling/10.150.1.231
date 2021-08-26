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
//    $ac_no = $_POST['ac_no'];
    $supplier_id = $_POST['supplier_id'];
    $amount = $_POST['amount'];
    $acpty_id = $_POST['acpty_id'];
    $acty_id = $_POST['acty_id'];
    $justification = mysql_real_escape_string(strtoupper(utf8_encode($_POST['justification'])));
    $terms = mysql_real_escape_string(strtoupper(utf8_encode($_POST['terms'])));
    $user_id = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    $prepaid = '1';

    if (mysql_query("INSERT INTO `adv`(`supplier_id`, `branch_id`, `amount`, `acpty_id`, `acty_id`, `justification`, `terms`,`prepaid`,`user_id`, `date`, `date_processed`)
        VALUES ('$supplier_id','7','$amount','$acpty_id','$acty_id','$justification','$terms','$prepaid','$user_id','$date','$date')")) {
    } else {
        $err++;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'submitEditForm') {
    $ac_id = $_GET['ac_id'];
    $supplier_id = $_POST['supplier_id'];
    $amount = $_POST['amount'];
    $acpty_id = $_POST['acpty_id'];
    $acty_id = $_POST['acty_id'];
    $justification = mysql_real_escape_string(strtoupper(utf8_encode($_POST['justification'])));
    $terms = mysql_real_escape_string(strtoupper(utf8_encode($_POST['terms'])));
    $user_id = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    $prepaid = '1';

    if (mysql_query("UPDATE `adv` SET `ac_no`='$ac_no',`supplier_id`='$supplier_id',`amount`='$amount',`acpty_id`='$acpty_id',`acty_id`='$acty_id',`justification`='$justification',`terms`='$terms',prepaid='$prepaid' WHERE ac_id='$ac_id'")) {
        //do something
    } else {
        $err++;
    }
}
?>