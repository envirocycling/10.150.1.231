<?php
include 'config.php';
mysql_query("DELETE FROM scale_receiving_details WHERE detail_id='" . $_GET['detail_id'] . "'");
?>
<script>
    alert('Successfully Deleted.');
    location.replace('edit_transaction.php?trans_id=<?php echo $_GET['trans_id']; ?>');
</script>