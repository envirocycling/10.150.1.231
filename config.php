<?php
//$con = mysql_connect("10.151.16.230", "efi", "3fiS3rv3r");
$con = mysql_connect("10.150.1.230", "branches", "enviro101");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("efi_pamp", $con);

//$conn = mysqli_connect('10.151.16.230', 'efi', '3fiS3rv3r', 'efi_pamp');
$conn = mysqli_connect('10.150.1.230', 'branches', 'enviro101', 'efi_pamp');
if (!$conn) {
    trigger_error('mysqli Connection failed! ' . htmlspecialchars(mysqli_connect_error()), E_USER_ERROR);
} else {
	
}

mysqli_set_charset($conn, "utf8");
?>
