<?php



include 'config.php';

 $query = "SELECT sum(weight) as weg ,month_delivered FROM sup_deliveries where year_delivered ='".$_SESSION['year']."' group by month_delivered order by date_delivered asc;"; 
$result = mysqli_query($con, $query);


?>