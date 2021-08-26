<?php

@session_start();
include 'config.php';

$que = preg_split("[_]", $_GET['sup_id']);
echo "<h2>$que[1] Pricing</h3>";
echo "<table border='1' width='250'>";
echo "<input type='hidden' name='sup_id' value='$que[0]'>";
$ctr = 0;
$sql_mat = mysql_query("SELECT * FROM material WHERE status!='deleted' && code!='OTHERS'");
while ($rs_mat = mysql_fetch_array($sql_mat)) {
    $sql_price = mysql_query("SELECT * FROM suppliers_price WHERE material_id='" . $rs_mat['material_id'] . "' and supplier_id='" . $que[0] . "' and date<='" . date("Y/m/d") . "' ORDER BY id DESC");
    $rs_price = mysql_fetch_array($sql_price);
    echo "<tr>";
    echo "<td>" . $rs_mat['code'] . "</td>";
    echo "<td>";
    echo "<div style='margin-left: 30px;'>";
    if ($rs_price['price'] == '') {
        $sql_def_price = mysql_query("SELECT * FROM default_price WHERE material_id='" . $rs_mat['material_id'] . "'");
        $rs_def_price = mysql_fetch_array($sql_def_price);
        echo $rs_def_price['price'];
    } else {
        echo $rs_price['price'];
    }
    echo "</div>";
    echo "</tr>";
    $ctr++;
}
echo "</table>";
?>
