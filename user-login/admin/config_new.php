<?php
$con = mysql_connect("192.168.10.200", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("efi_pamp", $con);
?>