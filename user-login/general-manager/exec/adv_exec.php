<?php

session_start();
date_default_timezone_set("Asia/Singapore");

include '../config.php';

if (!isset($_SESSION['gm_id'])) {
    echo "<script>location.replace('../../');</script>";
}

function updateBr($ac_id) {
    include '../config.php';
    $sql_adv = mysqli_query($conn, "SELECT branch_id FROM adv WHERE ac_id='$ac_id'");
    $rs_adv = mysqli_fetch_array($sql_adv);

    if ($rs_adv['branch_id'] != '7') {
        mysqli_query($conn, "UPDATE adv SET upt_br='0' WHERE ac_id='$ac_id'");
    }
}

if (isset($_GET['approve_id'])) {
    mysqli_query($conn, "UPDATE adv SET status='approved',approved_id='" . $_SESSION['user_id'] . "',approved_date='" . date('Y-m-d H:i:s') . "' WHERE ac_id='" . $_GET['approve_id'] . "'");
    updateBr($_GET['approve_id']);
    echo "<script>";
    echo "alert('Successfully Approved.');";
    // echo "location.replace('../adv_view.php?ac_id=" . $_GET['approve_id'] . "&ip_address=$ip_add');";
	echo "location.replace('../adv_view.php?ac_id=" . $_GET['approve_id'] . "');";
    echo "</script>";
}
if (isset($_GET['disapprove_id'])) {
    mysqli_query($conn, "UPDATE adv SET status='disapproved',approved_id='" . $_SESSION['user_id'] . "',approved_date='" . date('Y-m-d H:i:s') . "' WHERE ac_id='" . $_GET['disapprove_id'] . "'");
    updateBr($_GET['disapprove_id']);
    echo "<script>";
    echo "alert('Successfully disapproved.');";
    // echo "location.replace('../adv_view.php?ac_id=" . $_GET['disapprove_id'] . "&ip_address=$ip_add');";
	echo "location.replace('../adv_view.php?ac_id=" . $_GET['disapprove_id'] . "');";
    echo "</script>";
}
if (isset($_GET['cancel_id'])) {
    mysqli_query($conn, "UPDATE adv SET status='verified',approved_id='',approved_date='' WHERE ac_id='" . $_GET['cancel_id'] . "'");
    updateBr($_GET['cancel_id']);
    echo "<script>";
    echo "alert('Successfully disapproved.');";
    // echo "location.replace('../adv_view.php?ac_id=" . $_GET['cancel_id'] . "&ip_address=$ip_add');";
	echo "location.replace('../adv_view.php?ac_id=" . $_GET['cancel_id'] . "');";
    echo "</script>";
}
?>