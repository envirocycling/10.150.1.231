<?php

include 'config.php';
echo "<table border='1'>";
$sql = mysql_query("SELECT * FROM suppliers_price GROUP BY supplier_id");
while ($rs = mysql_fetch_array($sql)) {
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    echo "<tr>";
    echo "<td>" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>