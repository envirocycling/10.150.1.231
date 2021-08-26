<?php
include 'config.php';
mysql_query("UPDATE payment SET status='paid' WHERE payment_id='".$_POST['payment_id']."'");

?>
<script>
    alert('Successfully Send to Online Payment.');
    location.replace('index.php');
</script>