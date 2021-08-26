<?php include("config.php");
$num = 0;
$counter=0;
$parameter= $_POST['parameter'];
$branch="PAMPANGA";
$receiving_details=preg_split("/[|]/",$parameter);
array_pop($receiving_details);





foreach ($receiving_details as $var) {
    $receiving_detailslvl2=preg_split("/[+]/",$var);

     $supplier_id=$receiving_detailslvl2[0];
    $wp_grade=$receiving_detailslvl2[2];
    $supplier_name= $receiving_detailslvl2[1];
    if($wp_grade=='LCWL' || $wp_grade=='CHIPBOARD' ) {
        $wp_grade=$wp_grade;
    }else {
        $wp_grade=substr($wp_grade,2);

    }
    $weight=$receiving_detailslvl2[3];

    $date=$receiving_detailslvl2[4];
	$plate=$receiving_detailslvl2[11];
	

    $month_delivered=date("F",strtotime($receiving_detailslvl2[8]));
    $year_delivered=date("Y",strtotime($receiving_detailslvl2[9]));
    $day_delivered=date("j",strtotime($date));



    
	
	$id=$receiving_detailslvl2[6];
	$dt_id=$receiving_detailslvl2[7];
	
	$query2="SELECT * FROM supplier_details where supplier_id='$supplier_id'";
    $result2=mysql_query($query2);
    $row2 = mysql_fetch_array($result2);
   $row2['supplier_name'];

 $check = mysql_query("SELECT * from sup_deliveries WHERE trans_id='$id' And detail_id='$dt_id'") or die (mysql_error());
 
 if(mysql_num_rows($check) == 0){
    if(mysql_query("INSERT INTO sup_deliveries(trans_id,detail_id,supplier_id,supplier_name,supplier_type,bh_in_charge,wp_grade,weight,branch_delivered,date_delivered,month_delivered,year_delivered,day_delivered,plate_number)VALUES('$id','$dt_id','$supplier_id','".$row2['supplier_name']."','".$row2['classification']."','".$row2['bh_in_charge']."','$wp_grade','$weight','$branch','$date','$month_delivered','$year_delivered','$day_delivered','$plate')")) {
		$num=1;

    }
	}else{
		if(mysql_query("UPDATE sup_deliveries SET trans_id='$id',detail_id='$dt_id',supplier_id='$supplier_id',supplier_name='".$row2['supplier_name']."',supplier_type='".$row2['classification']."',bh_in_charge='".$row2['bh_in_charge']."',wp_grade='$wp_grade',weight='$weight',branch_delivered='$branch',date_delivered='$date',month_delivered='$month_delivered',year_delivered='$year_delivered',day_delivered='$day_delivered',plate_number='$plate' WHERE trans_id='$id' And detail_id='$dt_id'")){
		$num=1;

    }
	}
}


if($num == 1){
$str=$receiving_detailslvl2[10];
?>
<script>
window.top.location.href = "http://192.168.10.200/paymentsystem/user-login/admin/export/export_receiving.php?strno=<?php echo $str.'& tid='.$id.'& update=receiving';?>";
</script>
<?php
}else if($num == 0){
?>
<script>
alert("System Error.");
window.top.location.href = "http://192.168.10.200/paymentsystem/user-login/admin/update_ims.php";
</script>
<?php
}
?>