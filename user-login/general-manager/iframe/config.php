<?php
$con = mysql_connect("10.150.1.230", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("efi_pamp", $con);
?>