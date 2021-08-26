<?php
include 'config.php';
echo "<table>";
$sql_mat = mysql_query("SELECT * FROM material WHERE status!='deleted'");
while ($rs_mat = mysql_fetch_array($sql_mat)) {
    echo "<tr>";
    echo "<td>".$rs_mat['code'].": </td>";
    echo "<td><input type='text' name='' value=''></td>";
    echo "</tr>";
}
echo "</table>";
?>