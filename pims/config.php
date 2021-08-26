<?php

$con = @new mysqli('10.151.22.125', 'rmd', '12345678', 'dbrmd');

if ($con->connect_error) {
    echo "Error: " . $con->connect_error;
	exit();
}
//echo 'Connected to MySQL';



/*

$con = mysql_connect("localhost", "root", "",);
if (!$con) {
    die('Could not connect: ' .mysql_error());
}

mysql_select_db("efi_ims", $con);


 mysql_connect("localhost","root","") or die("cant connect");
 mysql_select_db("efi_ims") or die(mysql_error());
*/
?>