<?php

@session_start();
include 'config.php';
$rs_rec = $_POST['trans_id'];
$sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='$rs_rec'");
$rs_rec = mysql_fetch_array($sql_rec);
mysql_query("UPDATE `scale_receiving` SET `upload`='0',`up_paper`='0',`checked`='0' WHERE  trans_id='" . $_POST['trans_id'] . "'");

$c = 0;
$sql_detail = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='" . $_POST['trans_id'] . "'");
while ($rs_details = mysql_fetch_array($sql_detail)) {
    $prev_mat = $rs_details['material_id'];
    $prev_gross = $rs_details['gross'];
    $prev_tare = $rs_details['tare'];
    $prev_net_weight = $rs_details['net_weight'];
    $mat = $_POST['mat_id_' . $c];
    $gross = $_POST['gross_' . $c];
    $tare = $_POST['tare_' . $c];
    $net_weight = $_POST['net_weight_' . $c];
    mysql_query("UPDATE `scale_receiving_details` SET `material_id`='$mat',`gross`='$gross',`tare`='$tare',`net_weight`='$net_weight',`corrected_weight`='$net_weight' WHERE detail_id='" . $rs_details['detail_id'] . "'");
    $c++;
}
?>