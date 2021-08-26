<?php

$con = mysql_connect("10.151.22.75", "efi", "");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("tipco", $con);
?>
