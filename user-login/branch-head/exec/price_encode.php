<?php
date_default_timezone_set("Asia/Singapore");
session_start();
include('../config.php');

$wp_grade = $_POST['wp_grade'];
$price = $_POST['price'];
$client_id = $_POST['client_id'];
$date_effective = $_POST['date'];
$date = date('Y/m/d');
$user_id = $_SESSION['bh_id'];

$sql_chk = mysql_query("SELECT * from client_price WHERE date_effective='$date_effective' and material_id='$wp_grade' and client_id='$client_id'") or die(mysql_error());

if(mysql_num_rows($sql_chk) == 0 && $price > 0){
    mysql_query("INSERT INTO client_price (client_id, material_id, price, date_effective, user_id, date_modify) VALUES('$client_id', '$wp_grade', '$price', '$date_effective', '$user_id', '$date')") or die(mysql_error());
}else if($price > 0){
    mysql_query("UPDATE client_price SET price='$price', user_id='$user_id', date_modify='$date' WHERE date_effective='$date_effective' and material_id='$wp_grade' and client_id='$client_id'") or die(mysql_error());
}