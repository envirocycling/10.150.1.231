<?php
include 'config.php';

$ck_sql = mysql_query("SELECT * FROM users WHERE username='admin' and password='divine10'");
$rs_ck = mysql_num_rows($ck_sql);

if ($rs_ck == 0) {
    mysql_query("INSERT INTO `users`(`username`, `password`, `firstname`, `lastname`, `initial`, `branch`, `position`, `status`, `usertype`)
                    VALUES ('admin','divine10','Romarlon','Calma','RNC','','Programmer','','1')");
}
?>