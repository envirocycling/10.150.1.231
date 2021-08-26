
<?php

$hostname = '10.150.1.230';
$dbname = 'efi_pamp';
$username = 'branches';
$password = 'enviro101';

$dsn = "mysql:host={$hostname};dbname={$dbname}";

$pdo = new PDO($dsn, $username, $password);

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

?>
