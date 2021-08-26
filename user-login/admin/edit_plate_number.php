<?php
include 'config.php';

if(isset($_POST['submit'])) {
    mysql_query("UPDATE scale_receiving SET plate_number='".$_POST['plate_number']."',ts_fee='".$_POST['ts_fee']."' WHERE trans_id='".$_POST['trans_id']."'");
    $sql_c = mysql_query("SELECT * FROM plate_number WHERE plate_number='".$_POST['old_plate_number']."' and supplier_id='".$_POST['supplier_id']."'");
    $rs_c = mysql_fetch_array($sql_c);
    $sql = mysql_query("SELECT * FROM plate_number WHERE plate_number='".$_POST['plate_number']."' and supplier_id='".$_POST['supplier_id']."'");
    $c = mysql_num_rows($sql);
    if ($c <= 0) {
        mysql_query("INSERT INTO plate_number (plate_number,supplier_id,wheels) VALUES ('".$_POST['plate_number']."','".$_POST['supplier_id']."','".$rs_c['wheels']."')");
        echo "<script>";
        echo "location.replace('edit_transaction.php?trans_id=".$_POST['trans_id']."');";
        echo "</script>";
    } else {
        mysql_query("UPDATE plate_number SET ts_fee='' WHERE plate_number='".$_POST['plate_number']."' and supplier_id='".$_POST['supplier_id']."'");
        echo "<script>";
        echo "location.replace('edit_transaction.php?trans_id=".$_POST['trans_id']."');";
        echo "</script>";
    }
}

$sql = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='".$_GET['trans_id']."'");
$rs = mysql_fetch_array($sql);
$sql_sup = mysql_query("SELECT * FROM supplier WHERE id='".$rs['supplier_id']."'");
$rs_sup = mysql_fetch_array($sql_sup);
$sql_wheels = mysql_query("SELECT * FROM ts_fee WHERE fee_id='".$rs['ts_fee']."'");
$rs_wheels = mysql_fetch_array($sql_wheels);
echo "<form action='edit_plate_number.php' method='POST'>";
echo "<input type='hidden' name='trans_id' value='".$_GET['trans_id']."'>";
echo "<input type='hidden' name='supplier_id' value='".$rs['supplier_id']."'>";
echo "<input type='hidden' name='old_plate_number' value='".$rs['plate_number']."'>";
echo "<table width='400'>";
echo "<tr>";
echo "<td align='center'><h3>Edit Plate Number of ".$rs_sup['supplier_id']."_".$rs_sup['supplier_name']."</h3></td>";
echo "</tr>";
echo "<tr>";
echo "<td align='center'><b>Plate Number: ".$rs['plate_number']."</b></td>";
echo "</tr>";
echo "<tr>";
echo "<td align='center'><h3>Wheels: ".$rs_wheels['wheels']."</h3></td>";
echo "</tr>";
echo "<tr>";
echo "<td align='center'><b>New Plate Number: <input type='text' name='plate_number' value='' required></b></td>";
echo "<tr>";
echo "<tr>";
echo "<td align='center'><b>Wheels: 
    <select name='ts_fee'>
    <option value=''></option>";
$sql = mysql_query("SELECT * FROM ts_fee");
while ($rs = mysql_fetch_array($sql)) {
    echo "<option value='".$rs['fee_id']."'>".$rs['wheels']."</option>";
}
echo "</select>
</b></td>";
echo "<tr>";
echo "<tr>";
echo "<td align='center'><input type='submit' name='submit' value='Update'></td>";
echo "<tr>";
echo "</table>";
echo "</form>";
?>