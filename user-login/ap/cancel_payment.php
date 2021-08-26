<?php
include 'config.php';

$sql_check = mysql_query("SELECT pay_type FROM payment WHERE payment_id='" . $_GET['payment_id'] . "'");
$rs_check = mysql_fetch_array($sql_check);

if ($rs_check['pay_type'] == 'Receiving') {
    mysql_query("UPDATE payment SET status='cancelled' WHERE payment_id='" . $_GET['payment_id'] . "'");

    mysql_query("UPDATE scale_receiving SET status='generated' WHERE payment_id='" . $_GET['payment_id'] . "'");
}
if ($rs_check['pay_type'] == 'Advances') {
    mysql_query("UPDATE payment SET status='cancelled' WHERE payment_id='" . $_GET['payment_id'] . "'");

    mysql_query("UPDATE adv SET status='approved' WHERE payment_id='" . $_GET['payment_id'] . "'");
}
?>

<script>
    alert('Successfully Cancelled.');
    location.replace('<?php echo $_GET['page']; ?>.php');
</script>