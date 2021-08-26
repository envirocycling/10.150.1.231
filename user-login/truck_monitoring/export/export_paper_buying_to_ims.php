<?php
@session_start();
include 'config.php';

echo "<div align='center'>";
echo "<br><br><br>";
echo "<font color='Blue' size='30'>Sending Data to IMS</font>";
echo "<br>";
echo "<font color='Blue' size='30'>Please Wait</font>";
echo "<br>";
echo "<img src='../images/ajax-loader.gif'>";
echo "</div>";

$ctr = 0;

echo "<form action='http://ims.efi.net.ph/paper_buying_module_new.php' method='POST' name='myForm'>";
$sql_paid = mysql_query("SELECT * FROM scale_receiving WHERE date>='".$_GET['from']."' and date<='".$_GET['to']."' and status!='void'");
while ($rs_paid = mysql_fetch_array($sql_paid)) {
    $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='".$rs_paid['trans_id']."'");
    while ($rs_details = mysql_fetch_array($sql_details)) {
        echo "<input type='hidden' name='date$ctr' value='".$rs_paid['date']."'>";
        echo "<input type='hidden' name='priority_no$ctr' value='".$rs_paid['priority_no']."'>";
        $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='".$rs_paid['supplier_id']."'");
        $rs_sup = mysql_fetch_array($sql_sup);
        echo "<input type='hidden' name='supplier_id$ctr' value='".$rs_sup['supplier_id']."'>";
        echo "<input type='hidden' name='plate_number$ctr' value='".$rs_paid['plate_number']."'>";
        $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='".$rs_details['material_id']."'");
        $rs_mat = mysql_fetch_array($sql_mat);
        echo "<input type='hidden' name='wp_grade$ctr' value='".$rs_mat['code']."'>";
        echo "<input type='hidden' name='corrected_weight$ctr' value='".$rs_details['net_weight']."'>";
        echo "<input type='hidden' name='unit_cost$ctr' value='".$rs_details['price']."'>";
        echo "<input type='hidden' name='paper_buying$ctr' value='".$rs_details['net_weight']*$rs_details['price']."'>";
        echo "</tr>";
        $ctr++;
    }
}
echo "<input type='hidden' name='ctr' value='$ctr'>";
echo "<input type='hidden' name='branch' value='".$_SESSION['branch']."'>";
echo "<input type='hidden' name='from' value='".$_GET['from']."'>";
echo "<input type='hidden' name='to' value='".$_GET['to']."'>";
echo "</form>";
echo "
<script>
    document.myForm.submit();
</script>";
?>