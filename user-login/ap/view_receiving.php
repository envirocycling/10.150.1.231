<?php
include 'config.php';
$receiving = array ();
$trans_id = array();
$sql_pending = mysql_query("SELECT * FROM scale_receiving");
while ($rs_pending = mysql_fetch_array($sql_pending)) {
    if (!empty ($_POST[$rs_pending['trans_id']])) {
        array_push ($trans_id,$rs_pending['trans_id']);
        array_push ($receiving,$rs_pending['supplier_id']);
    }
}
$unique = array_unique($receiving);
$count = count($unique);

if ($count != 1) {
    echo "<script>
    alert('Error.');
    history.back();
    </script>";
} else {
    foreach ($trans_id as $pay) {
        echo $pay."<br>";
    }
}
?>