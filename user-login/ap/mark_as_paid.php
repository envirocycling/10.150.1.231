<?php
include 'config.php';
date_default_timezone_set("Asia/Singapore");
$trans_id = $_GET['trans_id'];
$ref = explode('_',$_GET['ref']);
$que = preg_split("[_]",$trans_id);
$date = date('Y/m/d');
foreach ($que as $q) {
    if(strpos(strtoupper($ref[0]), 'SBC')  !== false ){
        $check = 'SBC - Digibanker';
    }else{
        $check = $ref[0];
    }
    mysql_query("UPDATE scale_receiving SET status='paid', date_paid='$date', cheque_no='$check', voucher_no='".$ref[1]."' WHERE trans_id='$q'");
}
echo "<script>
alert ('Successfully Mark as Paid.');
location.replace('index.php');
</script>";
?>