<?php
include("cons.php");
$counter = 0;
$parameter = $_POST['parameter'];
$branch = $_POST['branch'];
$paper_buying_details = preg_split("/[|]/", $parameter);
array_pop($paper_buying_details);
$ctr = 0;

$insert_count = 0;
$actual_count = 0;
$delete_checker = 0;
foreach ($paper_buying_details as $var) {
    $paper_buying_detailslvl2 = preg_split("/[+]/", $var);
    $date_received = $paper_buying_detailslvl2[0];
    $date_received = date("Y/m/d", strtotime($date_received));
    $priority_number = $paper_buying_detailslvl2[1];
    $supplier_id = $paper_buying_detailslvl2[2];
    $supplier_name = $paper_buying_detailslvl2[3];
    $plate_number = $paper_buying_detailslvl2[4];
    $wp_grade = $paper_buying_detailslvl2[5];
    if ($wp_grade == 'LCWL' || $wp_grade == 'CHIPBOARD') {
        $wp_grade = $wp_grade;
    } else {
        $wp_grade = substr($wp_grade, 2);
    }
    $corrected_weight = $paper_buying_detailslvl2[6];
    $unit_cost = $paper_buying_detailslvl2[7];
    $paper_buying = $paper_buying_detailslvl2[8];
	$branch= $paper_buying_detailslvl2[9];
	$str= $paper_buying_detailslvl2[10];
	$detail_id= $paper_buying_detailslvl2[12];
	$trans_id= $paper_buying_detailslvl2[11];
    $date_to_delete = $date_received;


    echo $date_received.'<br />';
    echo $priority_number.'<br />';
    echo $supplier_id.'<br />';
    echo $supplier_name.'<br />';
    echo $plate_number.'<br />';
    echo $wp_grade.'<br />';
    echo $corrected_weight.'<br />';
    echo $unit_cost.'<br />';
    echo $paper_buying.'<br />';
	echo $branch.'<br />';
 
 
 $check = mysql_query("SELECT * from paper_buying WHERE trans_id='$trans_id' And detail_id='$detail_id'") or die (mysql_error());
 
 if(mysql_num_rows($check) == 0){
 
   if (mysql_query("INSERT INTO paper_buying(trans_id,detail_id,date_received,dr_number,priority_number,supplier_id,supplier_name,plate_number,wp_grade,corrected_weight,unit_cost,paper_buying,branch,notes,date_uploaded) VALUES('$trans_id','$detail_id','$date_received','$str','$priority_number','$supplier_id','$supplier_name','$plate_number','$wp_grade','$corrected_weight','$unit_cost','$paper_buying','$branch','','" . date("Y/m/d") . "')")) {
      $num=1;
	  }
	   
    }else if(mysql_num_rows($check) > 0){
		if (mysql_query("UPDATE paper_buying SET trans_id='$trans_id',detail_id='$detail_id',date_received='$date_received',dr_number='$str',priority_number='$priority_number',supplier_id='$supplier_id',supplier_name='$supplier_name',plate_number='$plate_number',wp_grade='$wp_grade',corrected_weight='$corrected_weight',unit_cost='$unit_cost',paper_buying='$paper_buying',branch='$branch',notes='',date_uploaded='" . date("Y/m/d") . "' WHERE  trans_id='$trans_id' And detail_id='$detail_id' ") ) {
      $num=1;
	  }
		}
  
}

if($num == 1){
include('config.php');
	
	$str= $paper_buying_detailslvl2[10];
	$trans_id= $paper_buying_detailslvl2[11];
mysql_query("UPDATE scale_receiving SET up_paper='1' WHERE trans_id='$trans_id' and str_no='$str'") or die (mysql_error());

echo "<script>";
echo "location.replace('export_paper_buying.php');";
echo "</script>";

}

?>
