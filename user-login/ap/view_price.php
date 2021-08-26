<link rel="stylesheet" type="text/css" href="css/pay_table.css" />

<?php
date_default_timezone_set("Asia/Singapore");
include 'config.php';

echo "<div class='payTable'>";
echo "<table>";
echo "<tr>";
echo "<td align='center' class='info'>Material</td>";
echo "<td align='center' class='info'>Price</td>";
echo "<td align='center' class='info'>Date Effective</td>";
echo "</tr>";
$ctr = 0;
$sql_mat_price = mysql_query("SELECT * FROM material WHERE status!='deleted' and code!='OTHERS'");
while ($rs_mat_price = mysql_fetch_array($sql_mat_price)) {
    $sql_sup_price = mysql_query("SELECT * FROM suppliers_price WHERE dt_id='" . $_GET['dt_id'] . "' and material_id='" . $rs_mat_price['material_id'] . "' and supplier_id='" . $_GET['supplier_id'] . "' and date<='" . $_GET['date'] . "' ORDER BY id DESC");
    $rs_sup_price_count = mysql_num_rows($sql_sup_price);
    $rs_sup_price = mysql_fetch_array($sql_sup_price);
    $sql_def_price = mysql_query("SELECT * FROM default_price WHERE material_id='" . $rs_mat_price['material_id'] . "'");
    $rs_def_price = mysql_fetch_array($sql_def_price);
    if ($rs_def_price['price'] != '' && $rs_sup_price['price'] != '') {
        echo "<tr>";
        echo "<td>" . $rs_mat_price['code'] . "</td>";
        if ($rs_sup_price_count == 0) {
            echo "<td>" . $rs_def_price['price'] . "</td>";
            echo "<td>" . date("Y/m/d") . "</td>";
        } else {
            echo "<td>" . $rs_sup_price['price'] . "</td>";
            echo "<td>" . date("M d, Y", strtotime($rs_sup_price['date'])) . "</td>";
        }
        echo "</tr>";
    }
    $ctr++;
}
echo "</tr>";
echo "</table>";
echo "</div>";
?>