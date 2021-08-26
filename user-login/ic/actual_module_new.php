<?php
include("cons.php");
$counter=0;
$parameter= $_POST['parameter'];
$actual_details=preg_split("/[|]/",$parameter);
array_pop($actual_details);


foreach ($actual_details as $var) {
    $actual_detailslvl2=preg_split("/[+]/",$var);
    $str=$actual_detailslvl2[0];
     $delivered_to=$actual_detailslvl2[1];
    $plate_number=$actual_detailslvl2[2];
    $wp_grade=$actual_detailslvl2[3];
    $weight=$actual_detailslvl2[4];
     $branch=$actual_detailslvl2[5];
    if ($branch=='NOVALICHES'){
   $branch="kaybiga";
    }
    if ($branch=='MAKATI') {
       $branch="pasay";
    }
    $date=$actual_detailslvl2[6];
    $mc=$actual_detailslvl2[7];
    $dirt=$actual_detailslvl2[8];
    $net_wt=$actual_detailslvl2[9];
    $remarks=$actual_detailslvl2[10];
	$id=$actual_detailslvl2[11];
 $dtld_id=$actual_detailslvl2[12];
	
	 $check = mysql_query("SELECT * from actual WHERE trans_id='$id' And detail_id='$dtld_id'") or die (mysql_error());
	 if(mysql_num_rows($check) > 0){
	 	if(mysql_query("UPDATE actual SET detail_id='$dtld_id',trans_id='$id',str_no='$str',date='$date',delivered_to='$delivered_to',plate_number='$plate_number',wp_grade='$wp_grade',weight='$weight',branch='$branch',mc='$mc',dirt='$dirt',net_wt='$net_wt',comments='$remarks',dr_number='$str' WHERE trans_id='$id' And detail_id='$dtld_id'")) {
        $counter++;
		$num=1;
    }
	 }else if(mysql_num_rows($check) == 0){
    if(mysql_query("INSERT INTO actual(detail_id,trans_id,str_no,date,delivered_to,plate_number,wp_grade,weight,branch,mc,dirt,net_wt,comments,dr_number) VALUES('$dtld_id','$id','$str','$date','$delivered_to','$plate_number','$wp_grade','$weight','$branch','$mc','$dirt','$net_wt','$remarks','$str')")) {
        $counter++;
		$num=1;
    }
	}else{$num=0;}


}
if($num == 1){
include('config.php');
 $id=$actual_detailslvl2[11];
 $dtld_id=$actual_detailslvl2[12];

if(mysql_query("UPDATE scale_outgoing SET upload='1' WHERE str_no='$str' and trans_id='$id'") or die(mysql_error())){
?>
<script>
location.replace('export_to_ims.php');
</script>
<?php
}
}

?>
