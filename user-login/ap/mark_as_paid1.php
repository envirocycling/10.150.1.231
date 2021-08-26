<?php
include 'config.php';
$trans_id = $_GET['trans_id'];
$que = preg_split("[_]",$trans_id);

foreach ($que as $q) {
    mysql_query("UPDATE scale_receiving SET status='paid' WHERE trans_id='$q'");
}
echo "<script>
alert ('Successfully Mark as Paid.');
location.replace('index.php');
</script>";
?>