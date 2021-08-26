<?php

@session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';

echo "<form action='http://paymentsystem.efi.net.ph/save_payment_online.php' method='POST' name='myForm'>";

//echo "<form action='http://10.151.5.57/onlinepaymentsystem/save_payment_online.php' method='POST' name='myForm'>";
// textbox sending online
$sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $_GET['pay_id'] . "'");
$rs_pay = mysql_fetch_array($sql_pay);
$sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_pay['supplier_id'] . "'");
$rs_sup = mysql_fetch_array($sql_sup);
$sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
$rs_code = mysql_fetch_array($sql_code);

$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
echo "<input type='hidden' name='url' value='$url'>";
echo "<input type='hidden' name='payment_id' value='" . $_SESSION['branch'] . "-" . $_GET['pay_id'] . "'>";
echo "<input type='hidden' name='bank_code' value='" . $rs_pay['bank_code'] . "'>";
echo "<input type='hidden' name='cheque_no' value='" . $rs_pay['cheque_no'] . "'>";
echo "<input type='hidden' name='voucher_no' value='SBC_" . $rs_code['code'] . "" . $rs_pay['voucher_no'] . "'>";
echo "<input type='hidden' name='cheque_name' value='" . $rs_pay['cheque_name'] . "'>";
echo "<input type='hidden' name='supplier_name' value='" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "'>";
echo "<input type='hidden' name='sub_total' value='" . $rs_pay['sub_total'] . "'>";
echo "<input type='hidden' name='ts_fee' value='" . $rs_pay['ts_fee'] . "'>";
echo "<input type='hidden' name='adjustments' value='" . $rs_pay['adjustments'] . "'>";
echo "<input type='hidden' name='grand_total' value='" . $rs_pay['grand_total'] . "'>";
echo "<input type='hidden' name='account_name' value='" . $rs_pay['account_name'] . "'>";
echo "<input type='hidden' name='account_number' value='" . $rs_pay['account_number'] . "'>";

if ($rs_pay['pay_type'] == 'Advances') {
    $sql_adv = mysql_query("SELECT * FROM adv WHERE payment_id='" . $rs_pay['payment_id'] . "'");
    $rs_adv = mysql_fetch_array($sql_adv);
    echo "<input type='hidden' name='ap' value='" . $rs_adv['branch_user'] . "-'>";
    echo "<input type='hidden' name='verifier' value='" . $rs_adv['branch_verifier'] . "-'>";
    echo "<input type='hidden' name='signatory' value='" . $rs_adv['branch_verifier'] . "-'>";
} else {
    $sql_users = mysql_query("SELECT * FROM users WHERE user_id='" . $rs_pay['ap'] . "'");
    $rs_users = mysql_fetch_array($sql_users);
    echo "<input type='hidden' name='ap' value='" . $rs_users['initial'] . "-" . $rs_users['firstname'] . " " . $rs_users['lastname'] . "'>";
    $sql_users = mysql_query("SELECT * FROM users WHERE user_id='" . $rs_pay['verifier'] . "'");
    $rs_users = mysql_fetch_array($sql_users);
    echo "<input type='hidden' name='verifier' value='" . $rs_users['initial'] . "-" . $rs_users['firstname'] . " " . $rs_users['lastname'] . "'>";
    $sql_users = mysql_query("SELECT * FROM users WHERE user_id='" . $rs_pay['signatory'] . "'");
    $rs_users = mysql_fetch_array($sql_users);
    echo "<input type='hidden' name='signatory' value='" . $rs_users['initial'] . "-" . $rs_users['firstname'] . " " . $rs_users['lastname'] . "'>";
}



echo "<input type='hidden' name='pay_type' value='" . $rs_pay['pay_type'] . "'>";
if ($rs_pay['pay_type'] == 'Receiving') {

    $ctr = 0;
    $sql_adj = mysql_query("SELECT * FROM payment_adjustment WHERE payment_id='" . $_GET['pay_id'] . "'");
    while ($rs_adj = mysql_fetch_array($sql_adj)) {
        echo "<input type='hidden' name='adj_type$ctr' value='" . $rs_adj['adj_type'] . "'>";
        echo "<input type='hidden' name='desc$ctr' value='" . $rs_adj['desc'] . "'>";
        echo "<input type='hidden' name='amount$ctr' value='" . $rs_adj['amount'] . "'>";
        $ctr++;
    }
    echo "<input type='hidden' name='ctr_adj' value='$ctr'>";

    $ctr = 0;
    $sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE payment_id='" . $_GET['pay_id'] . "'");
    while ($rs_rec = mysql_fetch_array($sql_rec)) {
       $sql_rec_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='" . $rs_rec['trans_id'] . "'");
        while ($rs_rec_details = mysql_fetch_array($sql_rec_details)) {
            $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_rec_details['material_id'] . "'");
            $rs_mat = mysql_fetch_array($sql_mat);

            echo "<input type='hidden' name='wp_grade$ctr' value='" . $rs_mat['code'] . "'>";
            echo "<input type='hidden' name='net_weight$ctr' value='" . $rs_rec_details['corrected_weight'] . "'>";
            echo "<input type='hidden' name='price$ctr' value='" . $rs_rec_details['price'] . "'>";
            echo "<input type='hidden' name='amount2$ctr' value='" . $rs_rec_details['amount'] . "'>";
            echo "<input type='hidden' name='adj_price$ctr' value='" . $rs_rec_details['adj_price'] . "'>";
            echo "<input type='hidden' name='adj_amount$ctr' value='" . $rs_rec_details['adj_amount'] . "'>";

            $ctr++;
		}
    }
    echo "<input type='text' name='rec_det_adj' value='$ctr'>";
}

if ($rs_pay['pay_type'] == 'Advances') {
    $ctr = 0;
    $sql_adj = mysql_query("SELECT * FROM payment_others WHERE payment_id='" . $_GET['pay_id'] . "'");
    while ($rs_adj = mysql_fetch_array($sql_adj)) {
        echo "<input type='hidden' name='adj_type$ctr' value='ADD'>";
        echo "<input type='hidden' name='desc$ctr' value='" . $rs_adj['particulars'] . "'>";
        echo "<input type='hidden' name='amount$ctr' value='" . $rs_adj['amount'] . "'>";
        $ctr++;
    }
    echo "<input type='hidden' name='ctr_adj' value='$ctr'>";
}
echo "</form>";
echo "
<script>
    document.myForm.submit();
</script>";
?>