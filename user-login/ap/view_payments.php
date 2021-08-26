<style>
    input{
        border-style:hidden;
        text-align:center;
        border-bottom: solid;
        border-width:1.5px;
        font-size: 15px;
    }
</style>

<?php
include 'config.php';

$sql_payment = mysql_query("SELECT * FROM payment WHERE payment_id='" . $_GET['payment_id'] . "'");
$rs_payment = mysql_fetch_array($sql_payment);

$sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_payment['supplier_id'] . "'");
$rs_sup = mysql_fetch_array($sql_sup);

$sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
$rs_code = mysql_fetch_array($sql_code);

echo "<center>";
echo "<h1>Payment Order</h1>";
echo "<table width='700'>";
echo "<tr>";
echo "<td align='center'>Date: <input type='text' value='" . $rs_payment['date'] . "' size='20' name='date' readonly></td>";
echo "<td align='center'>Bank: <input type='text' value='" . $rs_payment['bank_code'] . "' size='20' name='bank' readonly></td>";
echo "<td align='center'>CV#: <input type='text' value='SBC_" . $rs_code['code'] . "" . $rs_payment['voucher_no'] . "' size='20' name='voucher_no' readonly></td>";
echo "</tr>";
echo "</table>";
echo "<table width='700'>";
echo "<tr>";
echo "<td align='center'>Supplier: <input type='text' value='" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "' size='20' name='supplier' readonly></td>";
echo "<td align='center'>Acct Name: <input type='text' value='" . $rs_payment['account_name'] . "' size='20' name='voucher_no' readonly></td>";
echo "<td align='center'>Acct #: <input type='text' value='" . $rs_payment['account_number'] . "' size='20' name='voucher_no' readonly></td>";
echo "</tr>";
echo "</table>";
echo "<hr>";
if ($rs_payment['pay_type'] == 'Receiving') {
    echo "<h2>Delivery Breakdown</h2>";
    echo "<table>";
    echo "<tr>";
    echo "<tr>";
    echo "<td><b>WP Grade</b></td>";
    echo "<td><b>Weight</b></td>";
    echo "<td></td>";
    echo "<td><b>Price</b></td>";
    echo "<td></td>";
    echo "<td><b>Amount</b></td>";
    echo "</tr>";
    $sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE payment_id='" . $_GET['payment_id'] . "'");
    while ($rs_rec = mysql_fetch_array($sql_rec)) {
        $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='" . $rs_rec['trans_id'] . "'");
        while ($rs_details = mysql_fetch_array($sql_details)) {
            $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_details['material_id'] . "'");
            $rs_mat = mysql_fetch_array($sql_mat);
            echo "<tr>";
            echo "<td align='center'>" . $rs_mat['code'] . "</td>";
            echo "<td align='center'>" . $rs_details['corrected_weight'] . "</td>";
            echo "<td align='center'>--------------------</td>";
            echo "<td align='center'>" . $rs_details['price'] . "</td>";
            echo "<td align='center'>--------------------</td>";
            echo "<td align='center'>" . $rs_details['amount'] . "</td>";
            echo "</tr>";
        }/*
		 echo "<tr>";
            echo "<td align='center'>" . $rs_rec['description'] . "</td>";
            echo "<td align='center'>" . $rs_rec['quantity'] . "</td>";
            echo "<td align='center'>--------------------</td>";
            echo "<td align='center'>" . $rs_rec['unit_price'] . "</td>";
            echo "<td align='center'>--------------------</td>";
            echo "<td align='center'>" . $rs_rec['amount'] . "</td>";
            echo "</tr>";*/
    }
    echo "</table>";
    echo "---------------------------------------------------------------------------------------------------------------------";
    echo "<h2>Sub Total: " . $rs_payment['sub_total'] . "</h2>";
    echo "***********************************************************************************";
    echo "<h2>Adjustments</h2>";
    echo "<table>";
    $sql_adj = mysql_query("SELECT * FROM payment_adjustment WHERE payment_id='" . $_GET['payment_id'] . "'");
    while ($rs_adj = mysql_fetch_array($sql_adj)) {
        echo "<tr>";
        echo "<td><b>" . $rs_adj['desc'] . ":</b></td>";
        echo "<td>-----------------------------------------</td>";
        if ($rs_adj['adj_type'] == 'add') {
            echo "<td>" . $rs_adj['amount'] . "</td>";
        } else {
            echo "<td>(" . $rs_adj['amount'] . ")</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

if ($rs_payment['pay_type'] == 'Advances') {
    echo "<h2>Breakdown</h2>";
    echo "<table>";
    echo "<tr>";
    echo "<td><b>Particulars:</b></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td><b>Amount</b></td>";
    echo "</tr>";
    $sql_adj = mysql_query("SELECT * FROM payment_others WHERE payment_id = '" . $_GET['payment_id'] . "'");
    while ($rs_adj = mysql_fetch_array($sql_adj)) {
        echo "<tr>";
        echo "<td><b>" . $rs_adj['particulars'] . ":</b></td>";
        echo "<td>&nbsp;&nbsp;&nbsp;</td>";
        echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        echo "<td>" . $rs_adj['amount'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
echo " ---------------------------------------------------";
echo "<h2>Grand Total: " . $rs_payment['grand_total'] . "</h2>";
echo "<hr>";
$sql_users = mysql_query("SELECT * FROM users WHERE user_id = '" . $rs_payment['ap'] . "'");
$rs_users = mysql_fetch_array($sql_users);
echo "<table>";
echo "<tr>";
echo "<td align = 'center'>AP: <input type = 'text' name = 'ap' value = '" . $rs_users['initial'] . "-" . $rs_users['firstname'] . " " . $rs_users['lastname'] . "' readonly></td>";
echo "</tr>";
$sql_users = mysql_query("SELECT * FROM users WHERE user_id = '" . $rs_payment['signatory'] . "'");
$rs_users = mysql_fetch_array($sql_users);
echo "<tr>";
echo "<td align = 'center'>Signatory: <input type = 'text' name = 'signatory' value = '" . $rs_users['initial'] . "-" . $rs_users['firstname'] . " " . $rs_users['lastname'] . "' readonly></td>";
echo "</tr>";
echo "</table>";
echo "</center>";
?>
