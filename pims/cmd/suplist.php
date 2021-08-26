<?php
include 'config.php';
$user=$_SESSION['username'];
$pass=$_SESSION['pass'];


 $query = "SELECT Remarks,Truck_no,Invoice,RM_Type,Location,Weight,Bales,Ave FROM tblrmd"; 
$result = mysqli_query($con, $query);



?>