<?php

$file = '/var/www/html/paymentsystem/tasks/test.txt';
$x = file_put_contents($file, 'testing 123...' . PHP_EOL, FILE_APPEND | LOCK_EX);


?>
