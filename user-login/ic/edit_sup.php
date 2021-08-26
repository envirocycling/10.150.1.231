<link rel="stylesheet" href="cbFilter/cbCss.css" />
<link rel="stylesheet" href="cbFilter/sup.css" />
<script src="cbFilter/jquery-1.8.3.js"></script>
<script src="cbFilter/jquery-ui.js"></script>
<script src="cbFilter/sup_combo.js"></script>
<?php
include 'config.php';

if (isset($_POST['submit'])) {
    mysql_query("UPDATE scale_receiving SET supplier_id='" . $_POST['supplier_id'] . "' WHERE trans_id='" . $_GET['trans_id'] . "'");

    mysql_query("");
    echo "<script>
        alert('Successfully Save.');
location.replace('edit_transaction.php?trans_id=" . $_GET['trans_id'] . "');
</script>";
}

$sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='" . $_GET['trans_id'] . "'");
$rs_trans = mysql_fetch_array($sql_trans);

$sql_sup_name = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_trans['supplier_id'] . "'");
$rs_sup_name = mysql_fetch_array($sql_sup_name);
echo "<center><h3>";
echo "Edit Transaction " . $_GET['trans_id'] . "</h3>";
echo "<h5>Prev Supplier Name: " . $rs_sup_name['supplier_id'] . "_" . $rs_sup_name['supplier_name'];
echo "</h5>";
echo "<form action='edit_sup.php?trans_id=" . $_GET['trans_id'] . "' method='POST'>";
echo "<h5>New Supplier Name: <span id='sup_picker'>";
echo "<select name='supplier_id' id='combobox' required>";
$sql_sup = mysql_query("SELECT * FROM supplier");
echo "<option value=''></option>";
while ($rs_sup = mysql_fetch_array($sql_sup)) {
    echo "<option value='" . $rs_sup['id'] . "'>" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</option>";
}
echo "</select>";
echo "</span>";
echo "<br><br>";
echo "<input type='submit' name='submit' value='Submit'>";
echo "</form>";
echo "</center>";
?>