<?php
$sl = "SHA & LON";
$sl = mysql_real_escape_string($sl);
echo $sl;
echo "<br>";
$sl = htmlspecialchars($sl, ENT_QUOTES);
echo $sl;

?>