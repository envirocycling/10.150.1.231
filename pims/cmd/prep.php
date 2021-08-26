<?php
include 'config.php';
$user=$_SESSION['username'];
$pass=$_SESSION['pass'];


 $query = "SELECT * FROM tblrmd WHERE Location like '%AC%' or Location like '%FA%' or Location like '%AG12%' or Location like '%AG20%' or Location like '%AB19%' or Location like '%AB20%' or Location like '%AG15%' or Location like '%AG16%' or Location like '%AG17%'"; 
$result = mysqli_query($con, $query);



?>