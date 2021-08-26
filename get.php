<?php
ini_set('max_execution_time', 1000);
include 'config.php';
$supplier_id = $_POST['supplier_id'];
$ctr = $_POST['ctr'];
$url = $_POST['url'];
$c = 0;
$sql =mysql_query("SELECT id FROM supplier WHERE supplier_id=''");
while ($rs = mysql_fetch_array($sql)) {
    if ($c < $ctr) {
        $supplier_id++;
        mysql_query("UPDATE `supplier` SET supplier_id='$supplier_id' WHERE id='".$rs['id']."'");
    }
}
?>
<script>
    alert('<?php echo $ctr; ?> supplier got a new id.');
    location.replace('http://<?php echo $url; ?>/ts/user-login/branch-head/supplier.php');
</script>