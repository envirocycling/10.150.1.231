<?php
$con = mysql_connect("192.168.13.5", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("truck_scale", $con);

$select = mysql_query("SELECT * from user") or die (mysql_error());
?>