<?php

//ini_set('max_execution_time', 10000000000000000);
date_default_timezone_set("Asia/Singapore");
include('config.php');

$sql_time = mysql_query("SELECT * from system_settings") or die(mysql_error());
$row_time = mysql_fetch_array($sql_time);

$my_date = date('Y/m/d');
$chkHolyDate = date('Y/m/d', strtotime('-1 day', strtotime($my_date)));
$holy_date = $row_time['holy_date'];
@$my_branch = $_GET['branch'];
@$my_branchid = $_GET['branch_id'];
@$my_expense = round($_GET['expense'], 2);

$myTime1 = date('H:i');
$myTime = strtotime($myTime1);
$time = strtotime($row_time['fund_cutofftime']);

$my_datecutoff = strtotime($row_time['fund_cutoffdate']);
$c_date = strtotime($my_date);


//if(!empty($my_expense)){
//	$sql_fundchk = mysql_query("SELECT * from fund_transfer WHERE date='$my_date' and branch_id='$my_branchid'") or die(mysql_error()); 
//	$row_fundchk = mysql_fetch_array($sql_fundchk);
//		if(mysql_num_rows($sql_fundchk) > 0){
//			mysql_query("UPDATE fund_transfer SET check_expense='$my_expense' WHERE ft_id='".$row_fundchk['ft_id']."'") or die(mysql_error());
//		}else{
//			mysql_query("INSERT INTO fund_transfer (branch_id,check_expense,date) VALUES('$my_branchid','$my_expense','$my_date')") or die(mysql_error());
//		}
//		mysql_query("UPDATE branches SET status='online' WHERE branch_id='$my_branchid'") or die(mysql_error());
//}
//if(empty($chk)){	
//$sql_branch = mysql_query("SELECT * from branches WHERE (branch_id='5' or branch_id='6' or branch_id='9') and status='' Order by branch_id Asc") or die(mysql_error());
//$date_prev = date('Y/m/d', strtotime('-1 day', strtotime($row_time['fund_cutoffdate'])));
//$sql_ctime = mysql_query("SELECT * from fund_available") or die(mysql_error());
// $row_ctime = mysql_fetch_array($sql_ctime);
// $ctime = $row_ctime['cutoff_time'];
if ($my_datecutoff != $c_date) {
    mysql_query("Update system_settings SET fund_cutoffdate='$my_date'") or die(mysql_error());
    $page2 = "bg_fund.php";
    $sec2 = "180";
    header("Refresh: $sec2; url=$page2");
} else {
    $sql_prev = mysql_query("SELECT * from fund_available ORDER BY date Desc LIMIT 1") or die(mysql_error());
    $row_prev = mysql_fetch_array($sql_prev);
    $date_prev = $row_prev['date'];
    
    if($chkHolyDate > $date_prev && $my_date > $chkHolyDate){
        $date_prev = date('Y/m/d', strtotime($holy_date));
    }
//    $date_prev = '2017/12/15';
    $time_prev = $row_prev['cutoff_time'];

    $cTime = $row_time['fund_cutofftime'];
    $cDate = $row_time['fund_cutoffdate'];
    $day_word = strtoupper(date('l', strtotime($date_prev)));
    $sundate = date('Y/m/d', strtotime('+1 day', strtotime($date_prev)));

    $sql_branch = mysql_query("SELECT * from branches WHERE status='' Order by branch_id Asc") or die(mysql_error());

    if (mysql_num_rows($sql_branch) > 0) {
        echo $myTime = date('H');
        echo $time = date('H', strtotime($cTime));
        $sql_process = mysql_query("SELECT * from fund_available WHERE date='$my_date' and amount > 0");
        if ($myTime == $time && mysql_num_rows($sql_process) < 1) {
            while ($row_branch = mysql_fetch_array($sql_branch)) {

                if ($row_branch['branch_id'] == '7') {
                    @$sqli_conn = mysqli_connect($row_branch['ip_address'], 'efi', 'enviro101', 'efi_pamp');
                    if ($day_word == 'SATURDAY') {
                        $sql_branchExpense = mysqli_query($sqli_conn, "SELECT sum(grand_total) as total_expense from payment WHERE ft='0' and bank_code not like '%SBC%' and type LIKE '%supplier%' and ((date='$cDate' and time <= '$cTime') or (date='$date_prev' and time > '$time_prev')) and date = '$sundate' and (status !='deleted' and status != 'cancelled') ");
                        $row_branchExpense = mysqli_fetch_array($sql_branchExpense);
                        echo "SELECT sum(grand_total) as total_expense from payment WHERE ft='0' and bank_code not like '%SBC%' and type LIKE '%supplier%' and ((date='$cDate' and time <= '$cTime') or (date='$date_prev' and time > '$time_prev')) and date = '$sundate' and (status !='deleted' and status != 'cancelled') <br>";
                    } else {
                        $sql_branchExpense = mysqli_query($sqli_conn, "SELECT sum(grand_total) as total_expense from payment WHERE ft='0' and bank_code not like '%SBC%' and type LIKE '%supplier%' and ((date='$cDate' and time <= '$cTime') or (date='$date_prev' and time > '$time_prev')) and (status !='deleted' and status != 'cancelled') ");
                        $row_branchExpense = mysqli_fetch_array($sql_branchExpense);
                        echo "SELECT sum(grand_total) as total_expense from payment WHERE bank_code not like '%SBC%' and type LIKE '%supplier%' and ((date='$cDate' and time <= '$cTime') or (date='$date_prev' and time > '$time_prev')) and (status !='deleted' and status != 'cancelled')<br> ";
                    }
                } else {
                    @$sqli_conn = mysqli_connect($row_branch['ip_address'], 'efi', 'enviro101', 'truck_scale');
                    if ($day_word == 'SATURDAY') {
                        $sql_branchExpense = mysqli_query($sqli_conn, "SELECT sum(grand_total) as total_expense from payment WHERE bank_code not like '%SBC%' and type LIKE '%supplier%' and ((date='$cDate' and time <= '$cTime') or (date='$date_prev' and time > '$time_prev')) annd date = '$sundate' and (status !='deleted' and status != 'cancelled') ");
                        $row_branchExpense = mysqli_fetch_array($sql_branchExpense);
                        echo "SELECT sum(grand_total) as total_expense from payment WHERE bank_code not like '%SBC%' and type LIKE '%supplier%' and ((date='$cDate' and time <= '$cTime') or (date='$date_prev' and time > '$time_prev')) annd date = '$sundate' and (status !='deleted' and status != 'cancelled') <br>";
                    } else {
                        $sql_branchExpense = mysqli_query($sqli_conn, "SELECT sum(grand_total) as total_expense from payment WHERE bank_code not like '%SBC%' and type LIKE '%supplier%' and ((date='$cDate' and time <= '$cTime') or (date='$date_prev' and time > '$time_prev')) and (status !='deleted' and status != 'cancelled') ");
                        $row_branchExpense = mysqli_fetch_array($sql_branchExpense);
                        echo "SELECT sum(grand_total) as total_expense from payment WHERE bank_code not like '%SBC%' and type LIKE '%supplier%' and ((date='$cDate' and time <= '$cTime') or (date='$date_prev' and time > '$time_prev')) and (status !='deleted' and status != 'cancelled') <br>";
                    }
                }
                if (!mysqli_connect_errno($sqli_conn)) {
                    $sql_fundchk = mysql_query("SELECT * from fund_transfer WHERE date='$cDate' and branch_id='" . $row_branch['branch_id'] . "'") or die(mysql_error());
                    $row_fundchk = mysql_fetch_array($sql_fundchk);
                    if (mysql_num_rows($sql_fundchk) > 0) {
                        echo $row_branch['branch_id'] . $row_branchExpense['total_expense'] . '=update<br>';
                        mysql_query("UPDATE fund_transfer SET check_expense='" . $row_branchExpense['total_expense'] . "' WHERE ft_id='" . $row_fundchk['ft_id'] . "'") or die(mysql_error());
                    } else {
                        echo $row_branch['branch_id'] . $row_branchExpense['total_expense'] . '=insert<br>';
                        mysql_query("INSERT INTO fund_transfer (branch_id,check_expense,date) VALUES('" . $row_branch['branch_id'] . "','" . $row_branchExpense['total_expense'] . "','$cDate')") or die(mysql_error());
                    }
                }
            }
        } else {
            $page2 = "bg_fund.php";
            $sec2 = "180";
            header("Refresh: $sec2; url=$page2");
        }
    }
}
//}
?>