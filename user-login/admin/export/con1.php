<?php
$con = mysql_connect("10.151.16.230", "efi", "3fiS3rv3r");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("ims", $con);
?>
