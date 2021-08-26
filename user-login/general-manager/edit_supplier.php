<?php

include 'config.php';

if (isset($_POST['submit'])) {
    $sup_id = $_POST['sup_id'];
    $supplier_id = $_POST['supplier_id'];
    $supplier_name = $_POST['supplier_name'];
    $owner_name = $_POST['owner_name'];
    $owner_contact = $_POST['owner_contact'];
    $street = $_POST['street'];
    $municipality = $_POST['municipality'];
    $province = $_POST['province'];
    mysql_query("UPDATE `supplier` SET `supplier_id`='$supplier_id',`supplier_name`='$supplier_name',`owner_name`='$owner_name',`owner_contact`='$owner_contact',`street`='$street',`municipality`='$municipality',`province`='$province' WHERE `id`='$sup_id'");

    echo "<script>";
    echo "location.replace('iframe/query_suppliers.php?branch=" . $_GET['branch'] . "');";
    echo "</script>";
}

$sql = mysql_query("SELECT * FROM supplier WHERE id='" . $_GET['sup_id'] . "'");
$rs = mysql_fetch_array($sql);

echo "<form action='../edit_supplier.php?branch=" . $rs['branch'] . "' method='POST'>";

echo "<h3>Edit " . $rs['supplier_name'] . "</h3>";
echo "<input type='hidden' name='sup_id' value='" . $rs['id'] . "'>";
echo "<table>";
echo "<tr>";
echo "<td>Supplier ID: </td>";
echo "<td><input type='text' name='supplier_id' value='" . $rs['supplier_id'] . "'></td>";
echo "</tr>";
echo "<tr>";
echo "<td>Supplier Name: </td>";
echo "<td><input type='text' name='supplier_name' value='" . $rs['supplier_name'] . "'></td>";
echo "</tr>";
echo "<tr>";
echo "<td>Owner: </td>";
echo "<td><input type='text' name='owner_name' value='" . $rs['owner_name'] . "'></td>";
echo "</tr>";
echo "<tr>";
echo "<td>Contact: </td>";
echo "<td><input type='text' name='owner_contact' value='" . $rs['owner_contact'] . "'></td>";
echo "</tr>";
echo "<tr>";
echo "<td>Street: </td>";
echo "<td><input type='text' name='street' value='" . $rs['street'] . "'></td>";
echo "</tr>";
echo "<tr>";
echo "<td>Municipality: </td>";
echo "<td><input type='text' name='municipality' value='" . $rs['municipality'] . "'></td>";
echo "</tr>";
echo "<tr>";
echo "<td>Province: </td>";
echo "<td><input type='text' name='province' value='" . $rs['province'] . "'></td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='2'><input type='submit' name='submit' value='Update'></td>";
echo "</tr>";
echo "</table>";
echo "</form>";
?>
