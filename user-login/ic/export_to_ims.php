<?php  
 $page = $_SERVER['PHP_SELF'];
 $sec = "5";
 header("Refresh: $sec; url=$page");
?>
<table>
	<tr>
		<td><img src="images/magic_007.gif"></td>
	</tr>
	<tr>
		<td align="center">UPDATING IMS</td>
	</tr>
</table>
<?php
 $url = 'http://ims.efi.net.ph/pampanga_outgoing_module_new.php';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if (200==$retcode) {
include('config.php');  
  $result=mysql_query("SELECT * from scale_outgoing WHERE upload='0' and check='1' Order by trans_id Asc LIMIT 1") or die (mysql_error());

if(mysql_num_rows($result) > 0){

$parameter="";
$row=mysql_fetch_array($result);

	$select_supplier = mysql_query("SELECT * from supplier WHERE id='".$row['supplier_id']."' ") or die (mysql_error());
		$select_supplier_row = mysql_fetch_array($select_supplier);
		$branch_ = $select_supplier_row['branch'];
	
	$supplier_name =$select_supplier_row['supplier_name'];
	
	
		$select_dt = mysql_query("SELECT * from delivered_to WHERE dt_id='".$row['dt_id']."' ") or die (mysql_error());
	
		$select_dt_row = mysql_fetch_array($select_dt);
	
	$dt_id = $select_dt_row['name'];
	
	
	$select = mysql_query("SELECT * from scale_outgoing_details WHERE trans_id='".$row['trans_id']."'") or die (mysql_error());
echo "<form action='actual_module_new.php' method='POST' name='myForm'>";
		while($select_row = mysql_fetch_array($select)){
			
		$select_material = mysql_query("SELECT * from material WHERE material_id='".$select_row['material_id']."'") or die (mysql_error());
		$select_material_row = mysql_fetch_array($select_material);
		$material_ids = $select_material_row['code'];	
	
	$mc= 0;
	$dirt = 0;
    $str_no=$row['str_no'];
    $delivered_to=$dt_id;
    $plate_number=$row['plate_number'];
    $wp_grade=$material_ids;
   $weight=$select_row['net_weight'];
   $branch=$branch_;
    $date=$row['date'];
	if($select_row['weight_adj'] == 'reject'){
	$dirt = $select_row['less_weight'];
	}else if($select_row['weight_adj'] == 'moisture'){
	$mc=$select_row['less_weight'];
	}
    $net=$select_row['net_weight'];
	$remarks=$select_row['remarks'];
	$id = $row['trans_id'];
	$dtld_id=$select_row['detail_id'];
	

   $parameter.=$str_no."+".$delivered_to."+".$plate_number."+".$wp_grade."+".$weight."+".$branch."+".$date."+".$mc."+".$dirt."+".$net."+".$remarks."+".$id."+".$dtld_id."|";
   

}

echo "<input type='hidden' value='$parameter' name='parameter'>";
echo
"</form>";

echo "
<script>
    document.myForm.submit();
</script>
";
}
}else{
?>
<script>
location.replace("export_to_ims.php");
</script>
<?php
}
?>