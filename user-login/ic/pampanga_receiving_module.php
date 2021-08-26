<?php include("cons.php");
$counter=0;
$parameter= $_POST['parameter'];
$branch="PAMPANGA";
$receiving_details=preg_split("/[|]/",$parameter);
array_pop($receiving_details);

$date_to_delete="";
$branch_to_delete=$branch;
foreach ($receiving_details as $var) {
    $receiving_detailslvl2=preg_split("/[+]/",$var);
    $date_to_delete=$receiving_detailslvl2[4];

    break;
}
$date_to_delete=date("Y/m",strtotime($date_to_delete));



foreach ($receiving_details as $var) {
    $receiving_detailslvl2=preg_split("/[+]/",$var);

    echo $supplier_id=$receiving_detailslvl2[0];
    $wp_grade=$receiving_detailslvl2[2];
    $supplier_name= $receiving_detailslvl2[1];
    if($wp_grade=='LCWL' || $wp_grade=='CHIPBOARD' ) {
        $wp_grade=$wp_grade;
    }else {
        $wp_grade=substr($wp_grade,2);

    }
    $weight=$receiving_detailslvl2[3];

    $date=$receiving_detailslvl2[4];
	

    $month_delivered=date("F",strtotime($receiving_detailslvl2[8]));
    $year_delivered=date("Y",strtotime($receiving_detailslvl2[9]));
    $day_delivered=date("j",strtotime($date));



    
	
	$id=$receiving_detailslvl2[10];
	$dt_id=$receiving_detailslvl2[11];
	
	$query2="SELECT * FROM supplier_details where supplier_id='$supplier_id'";
    $result2=mysql_query($query2);
    $row2 = mysql_fetch_array($result2);
echo $row2['supplier_name'];

 $check = mysql_query("SELECT * from sup_deliveries WHERE trans_id='$id' And detail_id='$dt_id'") or die (mysql_error());
 
 if(mysql_num_rows($check) == 0){
    if(mysql_query("INSERT INTO sup_deliveries(trans_id,detail_id,supplier_id,supplier_name,supplier_type,bh_in_charge,wp_grade,weight,branch_delivered,date_delivered,month_delivered,year_delivered,day_delivered)VALUES('$id','$dt_id','$supplier_id','".$row2['supplier_name']."','".$row2['classification']."','".$row2['bh_in_charge']."','$wp_grade','$weight','$branch','$date','$month_delivered','$year_delivered','$day_delivered')")) {
		$num=1;

    }
	}else{
		if(mysql_query("UPDATE sup_deliveries SET trans_id='$id',detail_id='$dt_id',supplier_id='$supplier_id',supplier_name='".$row2['supplier_name']."',supplier_type='".$row2['classification']."',bh_in_charge='".$row2['bh_in_charge']."',wp_grade='$wp_grade',weight='$weight',branch_delivered='$branch',date_delivered='$date',month_delivered='$month_delivered',year_delivered='$year_delivered',day_delivered='$day_delivered' WHERE trans_id='$id' And detail_id='$dt_id'")){
		$num=1;

    }
	}

}


if($num == 1){
include('config.php');
$id=$receiving_detailslvl2[10];
$str=$receiving_detailslvl2[12];
mysql_query("UPDATE scale_receiving SET upload='1' WHERE trans_id='$id' and str_no='$str'") or die (mysql_error());

echo "<script>";
echo "location.replace('export_receiving.php');";
echo "</script>";

}
?>