<?php
include 'config.php';
$user=$_SESSION['username'];
$pass=$_SESSION['pass'];


$query = "SELECT * FROM tbluser WHERE userid= '$user' AND password='$pass'"; 
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);
if(is_null($row))
{

echo "Access Denied";

}  
else

{

header("location:./count_sheet.php");

}


?>
