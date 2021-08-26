<?php

session_start();
date_default_timezone_set("Asia/Singapore");

include '../config.php';

if (!isset($_SESSION['gm_id'])) {
    echo "<script>location.replace('../../');</script>";
}


if (isset($_GET['approve_id'])) {
    mysqli_query($conn, "UPDATE truck_penalty_reqremove SET status='approved', date_approved='" . date('Y-m-d H:i:s') . "' WHERE tpr_id='" . $_GET['approve_id'] . "'");
    echo "<script>";
    echo "alert('Successfully Approved.');";
    echo "location.replace('../truck_monitoring_view.php?tpr_id=" . $_GET['approve_id'] . "');";
    echo "</script>";
}
if (isset($_GET['disapprove_id'])) {
    mysqli_query($conn, "UPDATE truck_penalty_reqremove SET status='disapproved', date_approved='" . date('Y-m-d H:i:s') . "' WHERE tpr_id='" . $_GET['disapprove_id'] . "'");
    echo "<script>";
    echo "alert('Successfully disapproved.');";
    echo "location.replace('../truck_monitoring_view.php?tpr_id=" . $_GET['disapprove_id'] . "');";
    echo "</script>";
}

?>