<?php
@session_start();
include 'config.php';
mysql_query("INSERT INTO suppliers_price (`material_id`, `supplier_id`, `price`, `user_id`, `date`)
    VALUES ('".$_POST['mat']."','".$_POST['supplier_id']."','".$_POST['price']."','".$_SESSION['user_id']."','".date("Y/m/d")."')");
?>