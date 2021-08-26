<?php
@session_start();
include '../config.php';

echo "<div align='center'>";
echo "<br><br><br>";
echo "<font color='Blue' size='30'>Sending Data to IMS</font>";
echo "<br>";
echo "<font color='Blue' size='30'>Please Wait</font>";
echo "<br>";
echo "<img src='../images/ajax-loader.gif'>";
echo "</div>";

$ctr = 0;
echo "<form action='http://ims.efi.net.ph/receiving_module_new.php' method='POST' name='myForm'>";
$sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "' and status!='void'");
while ($rs_rec = mysql_fetch_array($sql_rec)) {
    $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='" . $rs_rec['trans_id'] . "'");
    while ($rs_details = mysql_fetch_array($sql_details)) {
        $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_rec['supplier_id'] . "'");
        $rs_sup = mysql_fetch_array($sql_sup);
        $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_details['material_id'] . "'");
        $rs_mat = mysql_fetch_array($sql_mat);
        $sql_ic = mysql_query("SELECT * FROM users WHERE user_id='" . $rs_rec['ic_in_charge'] . "'");
        $rs_ic = mysql_fetch_array($sql_ic);
        $sql_sic = mysql_query("SELECT * FROM users WHERE user_id='" . $rs_rec['sic_in_charge'] . "'");
        $rs_sic = mysql_fetch_array($sql_sic);
        echo "<input type='hidden' name='supplier_id$ctr' value='" . $rs_sup['supplier_id'] . "'>";
        echo "<input type='hidden' name='priority_no$ctr' value='" . $rs_rec['priority_no'] . "'>";
        echo "<input type='hidden' name='plate_number$ctr' value='" . $rs_rec['plate_number'] . "'>";
        echo "<input type='hidden' name='date$ctr' value='" . $rs_rec['date'] . "'>";
        echo "<input type='hidden' name='wp_grade$ctr' value='" . $rs_mat['code'] . "'>";
        echo "<input type='hidden' name='weight$ctr' value='" . $rs_details['net_weight'] . "'>";
        echo "<input type='hidden' name='c_weight$ctr' value='" . $rs_details['corrected_weight'] . "'>";
        echo "<input type='hidden' name='weight_adj$ctr' value='" . $rs_details['weight_adj'] . "'>";
        echo "<input type='hidden' name='mc_percentage$ctr' value='" . $rs_details['mc_percentage'] . "'>";
        echo "<input type='hidden' name='mc_weight$ctr' value='" . $rs_details['less_weight'] . "'>";
        echo "<input type='hidden' name='mc_percentage$ctr' value='" . $rs_details['mc_percentage'] . "'>";
        echo "<input type='hidden' name='encoder$ctr' value='" . $rs_ic['firstname'] . " " . $rs_ic['lastname'] . "'>";
        echo "<input type='hidden' name='shift_in_charge$ctr' value='" . $rs_sic['firstname'] . " " . $rs_sic['lastname'] . "'>";
        echo "<br>";
        $ctr++;
    }
}
echo "<input type='hidden' name='ctr' value='$ctr'>";
echo "<input type='hidden' name='branch' value='" . $_SESSION['branch'] . "'>";
echo "<input type='hidden' name='from' value='" . $_GET['from'] . "'>";
echo "<input type='hidden' name='to' value='" . $_GET['to'] . "'>";
echo "</form>";
echo "
<script>
    document.myForm.submit();
</script>";
?>