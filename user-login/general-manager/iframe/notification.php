<base target="_parent" />
<?php
@session_start();
include 'config.php';
mysql_query("UPDATE adjustments SET bh_noti='seened' WHERE bh_approval=''");
mysql_query("UPDATE add_mc SET bh_noti='seened' WHERE bh_approval=''");

$sql = mysql_query("SELECT * FROM p_online WHERE online_id='1'");
$rs = mysql_fetch_array($sql);
if ($rs['online'] == 'off') {
    $sql_payment = mysql_query("SELECT count(payment_id) FROM payment WHERE status='' and bank_code like '%SBC%'");
    $rs_payment = mysql_fetch_array($sql_payment);

    echo "There are " . $rs_payment['count(payment_id)'] . " supplier/s you need to check for Payment";
    echo "<br>
    <a href='../pending_payments.php'>Click here</a>
    <br>";
    echo "<hr>";
}
$sql_noti = mysql_query("SELECT * FROM adjustments WHERE bh_approval=''");
while ($rs_noti = mysql_fetch_array($sql_noti)) {
    $sql_users = mysql_query("SELECT * FROM users WHERE user_id='" . $rs_noti['user_id'] . "'");
    $rs_users = mysql_fetch_array($sql_users);
    $sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='" . $rs_noti['trans_id'] . "'");
    $rs_trans = mysql_fetch_array($sql_trans);
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_trans['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    echo ucfirst($rs_users['firstname']) . " " . ucfirst($rs_users['lastname']) . " wants to adjust the transaction of " . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "
         <br>Reason: " . $rs_noti['reason'] . "
         <br>Priority No.: " . $rs_trans['priority_no'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:" . $rs_trans['date'] . "</td>";
    echo "<br>
    <a href='../adjustments.php'>Click here</a>
    <br>";
    echo "<hr>";
}

$sql_noti = mysql_query("SELECT * FROM add_mc WHERE bh_approval=''");
while ($rs_noti = mysql_fetch_array($sql_noti)) {

    $sql_users = mysql_query("SELECT * FROM users WHERE user_id='" . $rs_noti['user_id'] . "'");
    $rs_users = mysql_fetch_array($sql_users);
    $sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='" . $rs_noti['trans_id'] . "'");
    $rs_trans = mysql_fetch_array($sql_trans);
    $sql_det = mysql_query("SELECT * FROM scale_receiving_details WHERE detail_id='" . $rs_noti['detail_id'] . "'");
    $rs_det = mysql_fetch_array($sql_det);
    $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_det['material_id'] . "'");
    $rs_mat = mysql_fetch_array($sql_mat);
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_trans['supplier_id'] . "'");
    echo ucfirst($rs_users['firstname']) . " " . ucfirst($rs_users['lastname']) . " add a moisture to the delivery " . $rs_mat['code'] . " of " . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "
            <br>
            Priority No.:" . $rs_trans['priority_no'] . "&nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            Date: " . $rs_trans['date'];
    echo "<br>
            <a href='../moisture.php'>Click here</a>
            <br>";
    echo "<hr>";
}
?>