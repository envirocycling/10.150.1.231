<style>
    .data{
        width: 100px;
    }
    .header{
        font-weight: bold;
    }
    .Remarks{
        width: 100px;
    }
    .details td{
        padding-left: 3px;
        padding-right: 3px;
    }
</style>

<?php
include 'configTPTS.php';

$sql_scale = mysql_query("SELECT * FROM scale WHERE scale_id='" . $_GET['scale_id'] . "'");
$rs_scale = mysql_fetch_array($sql_scale);

$sql_sup = mysql_query("SELECT * FROM supplier WHERE supplier_id='" . $rs_scale['supplier_id'] . "'");
$rs_sup = mysql_fetch_array($sql_sup);

$sql_dt = mysql_query("SELECT * FROM company WHERE company_id='" . $rs_scale['company_id'] . "'");
$rs_dt = mysql_fetch_array($sql_dt);

echo "<center>";
echo "<h2>Delivery of " . $rs_sup['name'] . "</h2>";
echo "<table class='rec'>";
echo "<tr>";
echo "<td>Date delivered: </td>";
echo "<td>" . $rs_scale['date'] . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Plate Number: </td>";
echo "<td>" . $rs_scale['plate_no'] . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>STR Number: </td>";
echo "<td>" . $rs_scale['str_no'] . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Delivered To: </td>";
echo "<td>" . $rs_dt['name'] . "</td>";
echo "</tr>";
echo "</table>";
echo "<br>";

echo "<table class='details' border='1'>";
echo "<tr class='header'>";
echo "<td>WP_Grade</td>";
echo "<td>Gross</td>";
echo "<td>Tare</td>";
echo "<td>Weight</td>";
echo "<td>Remarks</td>";
echo "</tr>";
$sql_det = mysql_query("SELECT * FROM scale_details WHERE scale_id='" . $rs_scale['scale_id'] . "'");
while ($rs_det = mysql_fetch_array($sql_det)) {

    $sql_com = mysql_query("SELECT * FROM commodity WHERE com_id='" . $rs_det['com_id'] . "'");
    $rs_com = mysql_fetch_array($sql_com);
    echo "<tr>";
    echo "<td>" . $rs_com['name'] . "</td>";
    echo "<td>" . $rs_det['gross'] . "</td>";
    echo "<td>" . $rs_det['tare'] . "</td>";
    echo "<td>" . $rs_det['net_weight'] . "</td>";
    echo "<td>" . $rs_det['remarks'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>