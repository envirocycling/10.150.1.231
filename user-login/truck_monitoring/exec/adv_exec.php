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
    $acty_id = $_POST['acty_id'];
    $justification = mysql_real_escape_string(strtoupper(utf8_encode($_POST['justification'])));
    $terms = mysql_real_escape_string(strtoupper(utf8_encode($_POST['terms'])));
    $user_id = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");

    $sql_ac_no = mysql_query("SELECT * FROM adv_sysgen_no");
    $rs_ac_no = mysql_fetch_array($sql_ac_no);
    $no = $rs_ac_no['sup_nx_ctrl_no'];

    $new_no = $no + 1;
    
    $sql_users = mysql_query("SELECT * from users WHERE position='General Manager'") or die(mysql_error());
    $row_users = mysql_fetch_array($sql_users);
    
    $sql_supp = mysql_query("SELECT * from supplier WHERE id='$supplier_id'") or die(mysql_error());
    $row_supp = mysql_fetch_array($sql_supp);
    $sql_branch = mysql_query("SELECT * from branches WHERE branch_name LIKE '%".$row_supp['branch']."%'") or die(mysql_error());
    $row_branch = mysql_fetch_array($sql_branch);
            
    if (mysql_query("INSERT INTO `adv`(`ac_no`, `supplier_id`, `branch_id`, `amount`, `justification`, `terms`,`prepaid`,`user_id`, `date`, `class`, `upt_br`, `verified_id`, `verified_date`, `approved_id`, `status`, `acty_id`)
        VALUES ('$ac_no','$supplier_id','".$row_branch['branch_id']."','$amount','$justification','$terms','$prepaid','$user_id','$date','truck registration','0', '$user_id', '$date', '".$row_users['user_id']."', 'verified', '1')") or die(mysql_error())) {
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

    if (mysql_query("UPDATE `adv` SET `ac_no`='$ac_no',`supplier_id`='$supplier_id',`amount`='$amount',`acpty_id`='$acpty_id',`acty_id`='$acty_id',`justification`='$justification',`terms`='$terms',prepaid='$prepaid', upt_br='0' WHERE ac_id='$ac_id'")) {
        //do something
    } else {
        $err++;
    }
}
?>