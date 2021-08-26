<?php
@session_start();
include '../config.php';
$ctr = 0;
echo "<form action='http://localhost/ims.efi.net.ph/receiving_tat_module_new.php' method='POST' name='myForm'>";
$sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE status!='void' and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
while ($rs_rec = mysql_fetch_array($sql_rec)) {
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_rec['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    echo "<input type='hidden' name='trans_id$ctr' value='".$_SESSION['branch']."-" . $rs_rec['trans_id'] . "'>";
    echo "<input type='hidden' name='date$ctr' value='" . $rs_rec['date'] . "'>";
    echo "<input type='hidden' name='priority_no$ctr' value='" . $rs_rec['priority_no'] . "'>";
    echo "<input type='hidden' name='supplier_id$ctr' value='" . $rs_sup['supplier_id']."'>";
    $sql_count = mysql_query("SELECT count(detail_id),sum(corrected_weight) FROM scale_receiving_details WHERE trans_id='".$rs_rec['trans_id']."'");
    $rs_count = mysql_fetch_array($sql_count);
    echo "<input type='hidden' name='no_of_grades$ctr' value='" . $rs_count['count(detail_id)'] . "'>";
    echo "<input type='hidden' name='actual_weight$ctr' value='" . $rs_count['sum(corrected_weight)'] . "'>";
    echo "<input type='hidden' name='arrival_time$ctr' value='" . date("h:i a",strtotime($rs_rec['arrival_time'])) . "'>";
    echo "<input type='hidden' name='start_time$ctr' value='" . date("h:i a",strtotime($rs_rec['start_time'])) . "'>";
    echo "<input type='hidden' name='finish_time$ctr' value='" . date("h:i a",strtotime($rs_rec['finish_time'])) . "'>";
    echo "<input type='hidden' name='queue_time$ctr' value='" . date("H:i",strtotime($rs_rec['queue_time'])) . "'>";
    echo "<input type='hidden' name='unload_time$ctr' value='" . date("H:i",strtotime($rs_rec['unload_time'])) . "'>";
    echo "<input type='hidden' name='total_time$ctr' value='" . date("H:i",strtotime($rs_rec['total_time'])) . "'>";
    $ctr++;
}
echo "<input type='hidden' name='ctr' value='$ctr'>";
echo "<input type='hidden' name='branch' value='".$_SESSION['branch']."'>";
echo "<input type='hidden' name='from' value='" . $_GET['from'] . "'>";
echo "<input type='hidden' name='to' value='" . $_GET['to'] . "'>";
echo "</form>";
echo "
<script>
    document.myForm.submit();
</script>";
?>