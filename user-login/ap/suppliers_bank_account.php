<?php
include 'config.php';
if (isset($_POST['submit'])) {
    mysql_query("INSERT INTO sup_bank_accounts (supplier_id,account_name,account_number)
        VALUES ('".$_POST['supplier_id']."','".$_POST['account_name']."','".$_POST['account_number']."')");
    echo "<script>";
    echo "location.replace('iframe/query_suppliers.php');";
    echo "</script>";
}
if(isset ($_GET['del_id'])) {
    mysql_query("DELETE FROM sup_bank_accounts WHERE bank_account_id='".$_GET['del_id']."'");
    echo "<script>";
    echo "location.replace('iframe/query_suppliers.php');";
    echo "</script>";
}
$que = preg_split("[_]",$_GET['sup_id']);
$sql = mysql_query("SELECT * FROM sup_bank_accounts WHERE supplier_id='$que[0]'");
echo "<center>";
echo "<h2>$que[1] Bank Account</h2>";
echo "<table width='300'>";
echo "<tr>";
echo "<td><b>Name</b></td>";
echo "<td><b>Number</b></td>";
echo "<td><b>Action</b></td>";
echo "</tr>";
while($rs = mysql_fetch_array($sql)) {
    echo "<tr>";
    echo "<td>".$rs['account_name']."</td>";
    echo "<td>".$rs['account_number']."</td>";
    echo "<td><a href='../suppliers_bank_account.php?del_id=".$rs['bank_account_id']."'>Delete</a></td>";
    echo "</tr>";
}
echo "</table>";
echo "<form action='../suppliers_bank_account.php' method='POST'>";
echo "<input type='hidden' name='supplier_id' value='$que[0]'>";
echo "<table width='350'>";
echo "<tr>";
echo "<td>Account Name: </td>";
echo "<td><input type='text' name='account_name' value=''></td>";
echo "</tr>";
echo "<td>Account Number: </td>";
echo "<td><input type='text' name='account_number' value=''></td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='2' align='center'><input type='submit' name='submit' value='Add'></td>";
echo "</tr>";
echo "</table>";
echo "</form>";
echo "</center>";
?>