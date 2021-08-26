<?php
date_default_timezone_set("Asia/Singapore");
session_start();
include('../config.php');

$sql_cutoff = mysql_query("SELECT * from system_settings") or die(mysql_error());
$row_cutoff = mysql_fetch_array($sql_cutoff);

$cutoff_date = $row_cutoff['fund_cutoffdate'];
$user_id = $_SESSION['ic_id'];
$myDate2 = date('Y/m/d');

@$av = $_POST['av'];
@$time = $_POST['time'];
@$myDate = $_POST['myDate'];
/*
@$transferred_2 = $_POST['cai'];
@$transferred_3 = $_POST['cal'];
@$transferred_4 = $_POST['cav'];
@$transferred_5 = $_POST['kay'];
@$transferred_12 = $_POST['man'];
@$transferred_9 = $_POST['pas'];
@$transferred_6 = $_POST['sau'];
@$transferred_10 = $_POST['urd'];

$addfund_2 = $_POST['af_cai'];
$addfund_3 = $_POST['af_cal'];
$addfund_4 = $_POST['af_cav'];
$addfund_5 = $_POST['af_kay'];
$addfund_12 = $_POST['af_man'];
$addfund_9 = $_POST['af_pas'];
$addfund_6 = $_POST['af_sau'];
$addfund_10 = $_POST['af_urd'];

$totreq_2 = $_POST['tr_cai'];
$totreq_3 = $_POST['tr_cal'];
$totreq_4 = $_POST['tr_cav'];
$totreq_5 = $_POST['tr_kay'];
$totreq_12 = $_POST['tr_man'];
$totreq_9 = $_POST['tr_pas'];
$totreq_6 = $_POST['tr_sau'];
$totreq_10 = $_POST['tr_urd'];
*/
$date_transfer = date('Y/m/d H:i');

$sql_av = mysql_query("SELECT * from fund_available WHERE date='$myDate'") or die(mysql_error());
$row_val = mysql_fetch_array($sql_av);
$sql_branch = mysql_query("SELECT * from branches ") or die(mysql_error());

	while($row_branch = mysql_fetch_array($sql_branch)){			
	
			$sql_chk = mysql_query("SELECT * from fund_transfer WHERE date='$myDate' and branch_id='".$row_branch['branch_id']."'") or die(mysql_error());
			$branch_id =  $row_branch['branch_id'];
			
			$addfund = str_replace(",","",$_POST['af_'.$branch_id]);
			$totreq = str_replace(",","",$_POST['tr_'.$branch_id]);
			$transferred = str_replace(",","",$_POST[$branch_id]);
			$exp = str_replace(",","",$_POST['ex_'.$branch_id]);
			$tot_mbal = str_replace(",","",$_POST['totmbal_'.$branch_id]);
					 
				if(mysql_num_rows($sql_chk) == 0){
					
					mysql_query("INSERT INTO fund_transfer (branch_id, additional_fund, total_request, user_id, date_transfer, transferred, date, check_expense, maintaining_balance)
						VALUES('".$row_branch['branch_id']."', '$addfund', '$totreq', '$user_id', '$date_transfer', '$transferred', '$myDate', '$exp', '$tot_mbal')") or die(mysql_error());
					
				}else{

					mysql_query("UPDATE fund_transfer SET additional_fund='$addfund', total_request='$totreq', user_id='$user_id', date_transfer='$date_transfer', transferred='$transferred', check_expense='$exp', maintaining_balance='$tot_mbal', up='1' WHERE date='$myDate' and branch_id='".$row_branch['branch_id']."' ") or die(mysql_error());
				
				}
	
	}
	
	if(mysql_num_rows($sql_av) == 0){
		mysql_query("INSERT INTO fund_available (amount, date, user_id,cutoff_time)
			VALUES('$av', '$myDate', '$user_id','$time')") or die(mysql_error());
	}else{
		mysql_query("UPDATE fund_available SET amount='$av', date='$cutoff_date', user_id='$user_id', cutoff_time='$time' WHERE date='$myDate' ") or die(mysql_error());
	}
?>