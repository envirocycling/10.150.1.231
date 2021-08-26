<?php
include 'config.php';
$user=$_SESSION['username'];
$pass=$_SESSION['pass'];


 $query = "SELECT * FROM branches"; 
$result = mysqli_query($con, $query);



?>