<?php
session_start();
if ($_SESSION['username']==="" || $_SESSION['username']===Null)
{
header("Location:index.php");

}
else
{
//header("Location:dashboard.php");

}

?>