<?php
ini_set('max_execution_time', 1000);
include 'config.php';
$c = $_GET['count'];
echo $c;
 $url = $_POST['url'];
//echo $c;
$ctr = 0;
$count =0;
$count_updated = 0;
echo "<div align='center'>";
echo "<br><br><br>";
echo "<font color='Blue' size='30'>Saving data from TS</font>";
echo "<br>";
echo "<font color='Blue' size='30'>Please Wait</font>";
echo "<br>";
echo "<img src='images/ajax-loader.gif'>";
echo "</div>";
while ($ctr < $c) {
    $supplier_id = $_POST['supplier_id'.$ctr];
    $supplier_name = $_POST['supplier_name'.$ctr];
    $branch = $_POST['branch'.$ctr];
    $owner_name = $_POST['owner_name'.$ctr];
    $owner_contact = $_POST['owner_contact'.$ctr];
    $classification = $_POST['classification'.$ctr];
    $street = $_POST['street'.$ctr];
    $municipality = $_POST['municipality'.$ctr];
    $province = $_POST['province'.$ctr];
    $bank = $_POST['bank'.$ctr];
    $account_name = $_POST['account_name'.$ctr];
    $account_number = $_POST['account_number'.$ctr];
    $date_added = $_POST['date_added'.$ctr];

    $sql_check = mysql_query("SELECT * FROM supplier WHERE supplier_id='$supplier_id'");
    $rs_check = mysql_num_rows($sql_check);
    if ($rs_check == 0) {
        mysql_query("INSERT INTO `supplier`(`supplier_id`, `supplier_name`, `branch`, `classification`, `owner_name`, `owner_contact`, `street`, `municipality`, `province`, `bank`, `account_name`, `account_number`, `date_added`)
        VALUES
        ('$supplier_id','$supplier_name','$branch','$classification','$owner_name','$owner_contact','$street','$municipality','$province','$bank','$account_name','$account_number','$date_added')");
        $count++;
    } else {
         mysql_query("UPDATE supplier SET supplier_name='$supplier_name',branch='$branch',classification='$classification',owner_name='$owner_name',owner_contact='$owner_contact',street='$street',municipality='$municipality',province='$province',bank='$bank',account_name='$account_name',account_number='$account_number' WHERE supplier_id='$supplier_id'");
        $count_updated++;
    }
    $ctr++;
}
?>

<script>
    alert('<?php echo $count; ?> suppliers imported, <?php echo $count_updated; ?> suppliers updated.');
    location.replace('http://<?php echo $url; ?>/ts/user-login/branch-head/supplier.php');
</script>