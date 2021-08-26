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
include 'config.php';

$sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='" . $_GET['trans_id'] . "'");
$rs_trans = mysql_fetch_array($sql_trans);
$sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_trans['supplier_id'] . "'");
$rs_sup = mysql_fetch_array($sql_sup);
$sql_dt = mysql_query("SELECT * FROM delivered_to WHERE dt_id='" . $rs_trans['dt_id'] . "'");
$rs_dt = mysql_fetch_array($sql_dt);

echo "<center>";
echo "<h2>Delivery of " . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</h2>";
echo "<table class='rec'>";
echo "<tr>";
echo "<td>Date delivered: </td>";
echo "<td>" . $rs_trans['date'] . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Plate Number: </td>";
echo "<td>" . $rs_trans['plate_number'] . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>STR Number: </td>";
echo "<td>" . $rs_trans['str_no'] . "</td>";
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
echo "<td>MC/Dirt</td>";
echo "<td>Net Weight</td>";
echo "<td>Remarks</td>";
echo "</tr>";
$sql_det = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='" . $_GET['trans_id'] . "'");
while ($rs_det = mysql_fetch_array($sql_det)) {
    $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_det['material_id'] . "'");
    $rs_mat = mysql_fetch_array($sql_mat);
//    echo "<tr>";
//    echo "<td><input class='data' type='text' name='' value='" . $rs_mat['code'] . "' readonly></td>";
//    echo "<td><input class='data' type='text' name='' value='" . $rs_det['gross'] . "' readonly></td>";
//    echo "<td><input class='data' type='text' name='' value='" . $rs_det['tare'] . "' readonly></td>";
//    echo "<td><input class='data' type='text' name='' value='" . $rs_det['net_weight'] . "' readonly></td>";
//    echo "<td><input class='data' type='text' name='' value='" . $rs_det['less_weight'] . "' readonly></td>";
//    echo "<td><input class='data' type='text' name='' value='" . $rs_det['corrected_weight'] . "' readonly></td>";
//    echo "<td><input class='data' type='text' name='' value='" . $rs_det['remarks'] . "' readonly></td>";
//    echo "</tr>";
    echo "<tr>";
    echo "<td>" . $rs_mat['code'] . "</td>";
    echo "<td>" . $rs_det['gross'] . "</td>";
    echo "<td>" . $rs_det['tare'] . "</td>";
    echo "<td>" . $rs_det['net_weight'] . "</td>";
    echo "<td>" . $rs_det['less_weight'] . "</td>";
    echo "<td>" . $rs_det['corrected_weight'] . "</td>";
    echo "<td>" . $rs_det['remarks'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>