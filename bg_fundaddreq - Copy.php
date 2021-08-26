<?php
date_default_timezone_set("Asia/Singapore");
include("config.php");

$sql_systmchk = mysql_query("SELECT * from system_settings") or die(mysql_error());
$row_systmchk = mysql_fetch_array(sql_systmchk);

$sql_cutoff = mysql_query("SELECT * from system_settings") or die(mysql_error());
$row_cutoff = mysql_fetch_array($sql_cutoff);

$afr_id = $_POST['afr_id']; 
$id = $_POST['id']; 
$branch = $_POST['branch'];
$amount =  $_POST['amount'];
$remarks = $_POST['remarks'];
$user_id = $_POST['user_id'];
$date = $_POST['date'];
$date_chk = date('Y/m/d', strtotime($date));
$user_name = $_POST['user_name'];
$verified_id = $_POST['verified_id'];
$verified_name = $_POST['verified_name'];
$verified_date = $_POST['verified_date'];
$row_branch['branch_id'];
$myDate = date('Y/m/d H:i');


$sql_branch = mysql_query("SELECT * from branches WHERE branch_name LIKE '%$branch%'") or die(mysql_error());
$row_branch = mysql_fetch_array($sql_branch);
if($row_systmchk['fund_updatecontrol'] == 0){
	
mysql_query("UPDATE system_settings SET fund_updatecontrol='1'") or die(mysql_error());

$sql_ftransfer = mysql_query("SELECT * from fund_transfer WHERE date='$date' and branch_id='".$row_branch['branch_id']."'") or die(mysql_error());

if(mysql_num_rows($sql_ftransfer) > 0){
		$sql_ftransfer = mysql_fetch_array($sql_ftransfer);
		$allocated = $sql_ftransfer['transferred'];
		$date_allocated = $sql_ftransfer['date_transfer'];
		
		//$url2 = "http://".$row_branch['ip_address']."/ts/fundaddreq_bg.php?afr=".$afr_id;
							$url2 = "http://192.168.13.5/ts/fundaddreq_bgchk.php";
							
							echo '<form action="'.$url2.'" method="post" name="myForm">
										<input type="text" name="id" value="'.$id.'">
										<input type="text" name="allocated" value="'.$allocated.'">
										<input type="text" name="date_allocated" value="'.$date_allocated.'">
										<input type="text" name="cutoff" value="'.$row_cutoff['fund_cutofftime'].'">
										<input type="text" name="connected" value="1">
							</form>';
							echo '<script>
									document.myForm.submit();
								</script>';
								
	}else if(mysql_num_rows($sql_ftransfer) == 0){
		$allocated='';
		mysql_query("UPDATE system_settings SET fund_updatecontrol='0'") or die(mysql_error());
				//mysql_query("UPDATE branches SET connected='1' WHERE branch_id='".$row_branch['branch_id']."'") or die(mysql_error());
				
				//$url2 = "http://".$row_branch['ip_address']."/ts/fundaddreq_bg.php";
				$url2 = "http://192.168.13.5/ts/fundaddreq_bgchk.php";
							
							echo '<form action="'.$url2.'" method="post" name="myForm">
										<input type="text" name="id" value="'.$id.'">
										<input type="text" name="cutoff" value="'.$row_cutoff['fund_cutofftime'].'">
										<input type="text" name="connected" value="1">
								</form>';
							echo '<script>
									document.myForm.submit();
								</script>';
	}	

	
$sql_fundreq = mysql_query("SELECT * from fund_adtl_request WHERE date LIKE '$date_chk%' and branch_id='".$row_branch['branch_id']."'") or die (mysql_error());
$row_fundreq = mysql_fetch_array($sql_fundreq);


			if(mysql_num_rows($sql_fundreq) == 0 && $amount > 0){
					if(mysql_query("INSERT INTO fund_adtl_request (afr_id_frm_branch, branch_id, amount, remarks, status, user_id, fullname, date, verified_id, verified_date, verified_name,sent_date)
									VALUES ('$afr_id' ,'".$row_branch['branch_id']."','$amount','$remarks','1','$user_id','$user_name','$date','$verified_id','$verified_date','$verified_name','$myDate')") or die (mysql_error())){	
							mysql_query("UPDATE system_settings SET fund_updatecontrol='0'") or die(mysql_error());
							
							//$url2 = "http://".$row_branch['ip_address']."/ts/fundaddreq_bg.php?afr=".$afr_id;
							$url2 = "http://192.168.13.5/ts/fundaddreq_bgchk.php";
							
							echo '<form action="'.$url2.'" method="post" name="myForm">
										<input type="text" name="afr_id" value="'.$afr_id.'">
										<input type="text" name="sent_date" value="'.$myDate.'">
										<input type="text" name="allocated" value="'.$allocated.'">
										<input type="text" name="date_allocated" value="'.$date_allocated.'">
										<input type="text" name="cutoff" value="'.$row_cutoff['fund_cutofftime'].'">
										<input type="text" name="connected" value="1">
								</form>';
							echo '<script>
									document.myForm.submit();
								</script>';
					}
			}else{
				
				mysql_query("UPDATE system_settings SET fund_updatecontrol='0'") or die(mysql_error());
				//mysql_query("UPDATE branches SET connected='1' WHERE branch_id='".$row_branch['branch_id']."'") or die(mysql_error());
				
				//$url2 = "http://".$row_branch['ip_address']."/ts/fundaddreq_bg.php";
				$url2 = "http://192.168.13.5/ts/fundaddreq_bgchk.php";
				echo '<script>
							window.top.location.href = "'.$url2.'";
					</script>';
				
			} 
}else{
	echo '<script>
			window.top.location.href = "http://192.168.13.5/ts/fundaddreq_bgchk.php";
		</script>';
}	

//window.top.location.href = "http://"'.$row_branch['ip_address'].'"/ts/fundaddreq_bgchk.php";
?>