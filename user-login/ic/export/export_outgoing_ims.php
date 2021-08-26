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
echo "<form action='http://ims.efi.net.ph/outgoing_module_new.php' method='POST' name='myForm'>";
$sql_out = mysql_query("SELECT * FROM scale_outgoing WHERE date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "' and status!='void'");
while ($rs_out = mysql_fetch_array($sql_out)) {
    $sql_details = mysql_query("SELECT * FROM scale_outgoing_details WHERE trans_id='" . $rs_out['trans_id'] . "'");
    while ($rs_details = mysql_fetch_array($sql_details)) {
        $sql_truckers = mysql_query("SELECT * FROM trucking WHERE trucking_id='" . $rs_out['trucking_id'] . "'");
        $rs_truckers = mysql_fetch_array($sql_truckers);
        $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_details['material_id'] . "'");
        $rs_mat = mysql_fetch_array($sql_mat);
        echo "<input type='hidden' name='str$ctr' value='" . $rs_details['str'] . "'>";
        echo "<input type='hidden' name='date$ctr' value='" . $rs_out['date'] . "'>";
        echo "<input type='hidden' name='priority_no$ctr' value='" . $rs_out['priority_no'] . "'>";
        echo "<input type='hidden' name='trucking$ctr' value='" . $rs_truckers['trucking_name'] . "'>";
        echo "<input type='hidden' name='plate_number$ctr' value='" . $rs_out['plate_number'] . "'>";
        echo "<input type='hidden' name='wp_grade$ctr' value='" . $rs_mat['code'] . "'>";
        echo "<input type='hidden' name='weight$ctr' value='" . $rs_details['net_weight'] . "'>";
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
//?>