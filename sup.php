<?php
include 'config.php';
$ctr = 0;
$sql = mysql_query("SELECT * FROM supplier");
echo "<form action='http://ims.efi.net.ph/sup_prov.php' method='POST'>";
while ($rs = mysql_fetch_array($sql)) {
    echo "<input type='name' name='sup_id_$ctr' value='".$rs['supplier_id']."'><input type='name' name='province_$ctr' value='".$rs['province']."'><br>";
    $ctr++;
}
echo "<input type='text' name='ctr' value='$ctr'>";
echo "<input type='submit' name='submit' value='Submit'>";
echo "</form>";
?>