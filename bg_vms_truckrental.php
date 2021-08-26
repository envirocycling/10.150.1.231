<?php 
date_default_timezone_set("Asia/Singapore");
include("config.php");

	$branch = 'PAMPANGA';
	$url = 'http://vms.efi.net.ph/vms_truckrental_bg.php?branch='.$branch.'';
	//$url = 'http://192.168.10.201/ts/vms_truckrental_bg.php?branch='.$branch.'';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_exec($ch);
	$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

$sql_settings = mysql_query("SELECT * from system_settings") or die(mysql_error());
$row_settings = mysql_fetch_array($sql_settings);

$s_month = $row_settings['truck_paymentmo'];
$c_month = date('m');
//$c_month = '11';
$c_monthyear = date('Y-m');
$c_date = date('Y-m-d');
$penalty_mo = date('Y/m', strtotime('-1 month', strtotime($c_date)));
$arr_total_volume = array();


if($s_month != $c_month){
	$sql_tmonitoring = mysql_query("SELECT * from truck_monitoring WHERE status=''") or die(mysql_error());
		while($row_tmonitoring = mysql_fetch_array($sql_tmonitoring)){
			
			$plate_number = strtoupper(str_replace(" ","",$row_tmonitoring['plate_no'])); 
			$sql_volume = mysql_query("SELECT * from scale_outgoing WHERE supplier_id='".$row_tmonitoring['supplier_id']."' and date LIKE '$penalty_mo%'") or die(mysql_error());
			while($row_volume = mysql_fetch_array($sql_volume)){
				$sql_volume_dtl = mysql_query("SELECT * from scale_outgoing_details WHERE trans_id='".$row_volume['trans_id']."'") or die(mysql_error());
				while($row_volume_dtl = mysql_fetch_array($sql_volume_dtl)){
                                    //$arr_total_volume[$plate_number] += $row_volume_dtl['corrected_weight'];
                                    //echo $row_volume_dtl['corrected_weight'].'<br>';
                                    $total_volume += $row_volume_dtl['corrected_weight'];
				}
			}
				$proposes_volume = $row_tmonitoring['proposed_volume'];
                                
                        $cashbond_month = date('Y-m-d', strtotime('+ '.$row_tmonitoring['cashbond_mo'].' month', strtotime($row_tmonitoring['issuance_date'])));
			$amort_month = date('Y-m-d', strtotime('+ '.$row_tmonitoring['rental_mo'].' month', strtotime($row_tmonitoring['issuance_date'])));
			
				if($total_volume < $proposes_volume){
                                    mysql_query("INSERT INTO truck_payment (tr_id, pay_name, type, amount, month, date) VALUES ('".$row_tmonitoring['tr_id']."', 'OTHER INCOME' ,'penalty', '".$row_tmonitoring['penalty']."', '$c_monthyear', '$c_date')") or die(mysql_error());
                                }
                                if($c_date <= $cashbond_month){
                                    mysql_query("INSERT INTO truck_payment (tr_id, pay_name, type, amount, month, date) VALUES ('".$row_tmonitoring['tr_id']."', 'SUPPLIER CASHBOND' ,'cashbond', '".$row_tmonitoring['cashbond']."', '$c_monthyear', '$c_date')") or die(mysql_error());	
                                }
                                if($c_date <= $amort_month){
                                    mysql_query("INSERT INTO truck_payment (tr_id, pay_name, type, amount, month, date) VALUES ('".$row_tmonitoring['tr_id']."', 'A/P-NT' ,'amortization', '".$row_tmonitoring['rental']."', '$c_monthyear', '$c_date')") or die(mysql_error());
                                }
					
		}
		
	mysql_query("UPDATE system_settings SET truck_paymentmo='$c_month'") or die(msql_error());
	
}
$num = 1;	
@$ctr = $_POST['ctr'];
if(@$ctr!=0){
	while($num < $ctr){
		$vms_id = $_POST['vms_id'.$num];
		$supplier_id1 = $_POST['supplier_id'.$num];
		$plate_no = $_POST['plate_no'.$num];
		$ref_no = $_POST['ref_no'.$num];
		$rental = $_POST['rental'.$num];
		$rental_mo = $_POST['rental_mo'.$num];
		$cashbond = $_POST['cashbond'.$num];
		$cashbond_mo = $_POST['cashbond_mo'.$num];
		$proposed_volume = $_POST['porposed_volume'.$num];
		$penalty = $_POST['penalty'.$num];
		$issuance_date = $_POST['issuance_date'.$num];
		$end_date = $_POST['end_date'.$num];
		$status = $_POST['status'.$num];
		$ref_nostatus = $_POST['ref_nostatus'.$num];
		$date_encoded = $_POST['date_encode'.$num];
			
			mysql_query("UPDATE truck_monitoring SET status='$status' WHERE vms_id='$vms_id' and status='' ") or die(mysql_error());
			
			$sql_supp = mysql_query("SELECT * from supplier WHERE supplier_id ='$supplier_id1'") or die(mysql_error());
			$row_supp = mysql_fetch_array($sql_supp);
			$supplier_id = $row_supp['id'];
		
			$slq_rentalchk = mysql_query("SELECT * from truck_monitoring WHERE vms_id='$vms_id' and status='' ") or die(mysql_error());
				
				if(mysql_num_rows($slq_rentalchk) == 0){
					 mysql_query("INSERT INTO truck_monitoring (vms_id, supplier_id, plate_no, ref_no, rental, rental_mo, cashbond, cashbond_mo, issuance_date, end_date, status, date_encoded, proposed_volume, penalty)
					 			VALUES('$vms_id','$supplier_id', '$plate_no', '$ref_no', '$rental', '$rental_mo', '$cashbond', '$cashbond_mo', '$issuance_date', '$end_date', '$status', '$date_encoded', '$proposed_volume', '$penalty') ") or die(mysql_error());
				}else{
					mysql_query("UPDATE truck_monitoring SET supplier_id='$supplier_id', plate_no='$plate_no', ref_no='$ref_no', rental='$rental', rental_mo='$rental_mo', cashbond='$cashbond', cashbond_mo='$cashbond_mo', issuance_date='$issuance_date', end_date='$end_date', status='$status', date_encoded='$date_encoded', proposed_volume='$proposed_volume', penalty='$penalty' WHERE vms_id='$vms_id' and status='' ") or die(mysql_error());
				}
                                
		$num++;
	}
				
				$page = "bg_vms_truckrental.php";
				$sec = "800";
				header("Refresh: $sec; url=$page");
}else if (200 == $retcode) {
   // echo '=========================';
//}else if (empty(@$ctr)) {
//		echo '<script>
//					setTimeout(function () {
//      					 window.location.href = "'.$url.'"; 
//    				}, 60000); 
//			</script>';
}else{
		$page = "bg_vms_truckrental.php";
		$sec = "800";
		header("Refresh: $sec; url=$page");
}
?>