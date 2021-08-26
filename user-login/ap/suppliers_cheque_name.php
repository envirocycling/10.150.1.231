<?php
include 'config.php';
if (isset($_POST['submit'])) {
    mysql_query("INSERT INTO cheque_name (supplier_id,name) VALUES ('".$_POST['supplier_id']."', '".$_POST['name']."')");
    echo "<script>";
    echo "location.replace('iframe/query_suppliers.php');";
    echo "</script>";
}
if(isset ($_GET['del_id'])) {
    mysql_query("DELETE FROM cheque_name WHERE id='".$_GET['del_id']."'");
    echo "<script>";
    echo "location.replace('iframe/query_suppliers.php');";
    echo "</script>";
}
$que = preg_split("[_]",$_GET['sup_id']);
$sql = mysql_query("SELECT * FROM cheque_name WHERE supplier_id='$que[0]'");
echo "<center>";
echo "<h2>$que[1] Cheque Name</h2>";
echo "<table width='300'>";
echo "<tr>";
echo "<td><b>Name</b></td>";
echo "<td><b>Action</b></td>";
echo "</tr>";
while($rs = mysql_fetch_array($sql)) {
    echo "<tr>";
    echo "<td>".$rs['name']."</td>";
    echo "<td><a href='../suppliers_cheque_name.php?del_id=".$rs['id']."'>Delete</a></td>";
    echo "</tr>";
}
echo "</table>";
echo "<form action='../suppliers_cheque_name.php' method='POST'>";
echo "<input type='hidden' name='supplier_id' value='$que[0]'>";
echo "<table width='350'>";
echo "<tr>";
echo "<td>Name: </td>";
echo "<td><input type='text' name='name' value=''></td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='2' align='center'><input type='submit' name='submit' value='Add'></td>";
echo "</tr>";
echo "</table>";
echo "</form>";
echo "</center>";
?>