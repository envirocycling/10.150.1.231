<?php
include 'config.php';
echo "<div align='center'>";
echo "<br><br><br>";
echo "<font color='Blue' size='30'>Please Wait..........</font>";
echo "</div>";
$branch = $_GET['branch'];
$ctr = 0;
echo "<form action='http://localhost/ts/import.php'' method='POST' name='myForm'>";
$sql = mysql_query("SELECT * FROM supplier_details WHERE branch='$branch' and status!='inactive'");
while ($rs = mysql_fetch_array($sql)) {
    echo "<input type='text' name='supplier_id".$ctr."' value='".$rs['supplier_id']."'>";
    echo "<input type='text' name='supplier_name".$ctr."' value='".$rs['supplier_name']."'>";
    echo "<input type='text' name='owner_name".$ctr."' value='".$rs['owner']."'>";
    echo "<input type='text' name='owner_contact".$ctr."' value='".$rs['owner_contact']."'>";
    echo "<input type='text' name='classification".$ctr."' value='".$rs['classification']."'>";
    echo "<input type='text' name='street".$ctr."' value='".$rs['street']."'>";
    echo "<input type='text' name='municipality".$ctr."' value='".$rs['municipality']."'>";
    echo "<input type='text' name='province".$ctr."' value='".$rs['province']."'>";
    echo "<input type='text' name='bank".$ctr."' value='".$rs['bank']."'>";
    echo "<input type='text' name='account_name".$ctr."' value='".$rs['account_name']."'>";
    echo "<input type='text' name='account_number".$ctr."' value='".$rs['account_number']."'>";
    echo "<input type='text' name='date_added".$ctr."' value='".$rs['date_added']."'>";
    echo "<br>";
    $ctr++;
}
echo "<input type='hidden' name='ctr' value='$ctr'>";
echo "</form>";
echo "
<script>
    document.myForm.submit();
</script>";
?>