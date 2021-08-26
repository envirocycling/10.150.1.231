<?php

date_default_timezone_set("Asia/Singapore");
include '../config.php';
$date = date("Y-m-d H:i:s");

if (isset($_GET['type']) && $_GET['type'] == 'saveRequest') {
    $sql_check = mysqli_query($conn, "SELECT * FROM `truck_penalty_reqremove` WHERE tr_id='" . $_POST['tr_id'] . "' and month='" . $_POST['month'] . "' and status=''");
    $rs_check = mysqli_fetch_array($sql_check);
    $rs_count = mysqli_num_rows($sql_check);

    $remarks = strtoupper($_POST['remarks']);

    if ($rs_count == 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO `truck_penalty_reqremove`(`tp_id`, `tr_id`, `month`, `amount`, `remarks`, `date`) VALUES (?,?,?,?,?,?)");
        $bind = mysqli_stmt_bind_param($stmt, "iisdss", $_POST['tp_id'], $_POST['tr_id'], $_POST['month'], $_POST['penalty'], $remarks, $date);
        $exec = mysqli_execute($stmt);
    }
    if ($rs_count > 0) {
        $stmt = mysqli_prepare($conn, "UPDATE `truck_penalty_reqremove` SET `amount`=?,`remarks`=?,`date`=? WHERE `tpr_id`=?");
        $bind = mysqli_stmt_bind_param($stmt, "dssi", $_POST['penalty'], $remarks, $date, $rs_check['tpr_id']);
        $exec = mysqli_execute($stmt);
    }
    if ($exec == false) {
        echo "Error SQL";
    }
}
if (isset($_GET['type']) && $_GET['type'] == 'cancelRequest') {
    mysqli_query($conn, "DELETE FROM truck_penalty_reqremove WHERE tpr_id='" . $_POST['tpr_id'] . "'");
    echo "DELETE FROM truck_penalty_reqremove WHERE tpr_id='" . $_POST['tpr_id'] . "'";
}
?>