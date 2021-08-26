<?php include("cons.php");
$counter=0;
$parameter= $_POST['parameter'];
$branch="Pampanga";
$receiving_details=preg_split("/[|]/",$parameter);
array_pop($receiving_details);
$date_to_delete="";
$branch_to_delete=$branch;
foreach ($receiving_details as $var) {
    $receiving_detailslvl2=preg_split("/[+]/",$var);
    $date_to_delete=$receiving_detailslvl2[1];

    break;
}
$date_to_delete=date("Y/m",strtotime($date_to_delete));




foreach ($receiving_details as $var) {

    $receiving_detailslvl2=preg_split("/[+]/",$var);
    $str=$receiving_detailslvl2[0];
    $date=$receiving_detailslvl2[1];
    $supplier=$receiving_detailslvl2[2];
    $plate_no=$receiving_detailslvl2[3];
    $wp_grade=$receiving_detailslvl2[4];
    $weight=$receiving_detailslvl2[5];
	$branch=$receiving_detailslvl2[6];


	$id=$receiving_detailslvl2[7];
	$dt_id=$receiving_detailslvl2[8];
	
	$check = mysql_query("SELECT * from outgoing WHERE trans_id='$id' And detail_id='$dt_id'") or die (mysql_error());
	
	if(mysql_num_rows($check) == 0){
	
    if(mysql_query("INSERT INTO outgoing(trans_id,detail_id,str,date,trucking,plate_number,wp_grade,weight,branch)
                                VALUES('$id','$dt_id','$str','$date','$supplier','$plate_no','$wp_grade','$weight','$branch')")) {
     
	 $num=1;
    }
	}else if(mysql_num_rows($check) > 0){
		 if(mysql_query("UPDATE outgoing SET trans_id='$id',detail_id='$dt_id',str='$str',date='$date',trucking='$supplier',plate_number='$plate_no',wp_grade='$wp_grade',weight='$weight',branch='$branch' WHERE trans_id='$id' And detail_id='$dt_id'")) {
     
	 $num=1;
    }
	}




}


if($num == 1){
include('config.php');
	
	$str=$receiving_detailslvl2[0];
	$dt_id=$receiving_detailslvl2[8];
mysql_query("UPDATE scale_outgoing SET up_out='1' WHERE trans_id='$id' and str_no='$str'") or die (mysql_error());

echo "<script>";
echo "location.replace('export_outgoing.php');";
echo "</script>";

}
?>