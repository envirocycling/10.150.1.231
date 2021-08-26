<?php

$hostname = '10.151.22.75';
$dbname = 'tipco';
$username = 'root';
$password = '';


$dsn = "mysql:host={$hostname};dbname={$dbname}";

$pdo = new PDO($dsn, $username, $password);

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

?>
