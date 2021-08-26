<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['ic_id'])) {
    echo "<script>location.replace('../../');</script>";
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <title>Envirocycling Fiber Inc.</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/ts_logo.png" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>
		<link rel="stylesheet" type="text/css" href="css/frm_fundtransfer.css" />
		<script type="text/javascript" src="js/fund_process2.js"></script>
    </head>

    <body>
        <div class="wrapper">
            <header class="header">
                <?php
					$sql_cutoff = mysql_query("SELECT * from system_settings") or die(mysql_error());
					$row_cutoff = mysql_fetch_array($sql_cutoff);
                include 'template/header.php';
                ?>
            </header><!-- .header-->

            <div class="middle" align="center">

                <?php
                include 'template/menu.php';
				echo '<br /><br /><br />';
					if(!isset($_POST['submit'])){
					$date2 = date('F d, Y');
					$myDate = date('Y/m/d');
				}else{
					$date2 = date('F d, Y' , strtotime($_POST['date']));
					$myDate = $_POST['date'];
				}
				
                ?>
				<form action="" method="post">
					<div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date: <input class="tcal" type="text" name="date" value="<?php echo $myDate;?>" size="10" id="myDate" required>&nbsp;&nbsp;&nbsp; <input type="submit" name="submit" style="width:100px; height:25px;"></div>
				   </form>
                <br><br>
                <h2>Process Fund Transfer</h2>
				<h4><?php echo $date2;?></h4>
				<h4><?php echo 'Cut Off Time- '.date('h:i A', strtotime($row_cutoff['fund_cutofftime']));?></h4>

                <br>
                <br> <br>
			<div class="frm_limit">
				<table class="frm_fundtransfer">
					<tr>
						<td>Branch</td>
						<td>Maintaining Balance</td>
						<td>Check Expense</td>
						<td>Remaining Fund</td>
						<td>Additional Fund</td>
						<td>Total Fund Request</td>
						<td>Allocated</td>
						<td>Action</td>
					</tr>
			<?php
				echo '<input type="hidden" value="0" id="hidden-count">';
				echo '<input type="hidden" value="0" id="hidden-count1">';
				echo '<input type="hidden" value="'.$row_cutoff['fund_cutofftime'].'" id="hidden-time">';
				$ofr = 0;
				
				//$myDate = date('Y/m/d');
				$sql_av = mysql_query("SELECT * from fund_available WHERE date='$myDate'") or die(mysql_error());
				$row_av = mysql_fetch_array($sql_av);
				$sql_branch = mysql_query("SELECT * from branches WHERE branch_id!='7' Order by branch_name Asc") or die(mysql_error());
				
				while($row_branch = mysql_fetch_array($sql_branch)){
					$sql_fund = mysql_query("SELECT * from fund_transfer WHERE branch_id = '".$row_branch['branch_id']."'  and date='$myDate'") or die(mysql_error());
					$row_fund = mysql_fetch_array($sql_fund);
					
					$sql_add = mysql_query("SELECT * from fund_adtl_request WHERE branch_id='".$row_branch['branch_id']."' and  date like '$myDate%'") or die(mysql_error());
						while($row_add = mysql_fetch_array($sql_add)){
								$arr_fundadd[$row_add['branch_id']] += $row_add['amount'];
						}
						
						if(empty($row_fund['maintaining_balance'])){
						$maintaining = $row_branch['maintaining_balance']; 
						$temp_m1 =$row_branch['maintaining_balance'];  
						}else{
						$maintaining = $row_fund['maintaining_balance']; 
						$temp_m2 =$row_fund['maintaining_balance'];
						}
						$tot_m = $temp_m2 + $temp_m1;
						$tot_maintaining += $tot_m; 
						$expense = $row_fund['check_expense'];
						$tot_expense += $row_fund['check_expense'];
						$add_fund = $arr_fundadd[$row_branch['branch_id']];
						if(empty($add_fund)){
							$add_fund = $row_fund['additional_fund'];
						}
						$remaining = $maintaining - $expense;
						$tot_remaining += $remaining;
						$total_fund = $add_fund + $expense;
						$tot_fund += $add_fund;
					    $ofr += $total_fund;
						$tansferred = $row_fund['transferred'];
						$tot_allo += $tansferred;
						
					if(empty($expense)){
						$expense = 0;
					}
					if(empty($add_fund)){
						$add_fund = 0;
					}
						echo '<input type="hidden" value="'.$row_branch['status'].'" id="hidden-status_'.$row_branch['branch_id'].'">';
						echo '<input type="hidden" value="'.$expense.'" id="hidden-expense_'.$row_branch['branch_id'].'">';
						echo '<input type="hidden" value="'.$add_fund.'" id="hidden-addfund_'.$row_branch['branch_id'].'">';
						echo '<input type="hidden" value="'.$maintaining.'" id="hidden-maintaining_'.$row_branch['branch_id'].'">';
						echo '<input type="hidden" value="'.$remaining.'" id="hidden-remaining_'.$row_branch['branch_id'].'">';
						echo '<input type="hidden" value="'.$total_fund.'" id="hidden-totfund_'.$row_branch['branch_id'].'">';
						echo '<input type="hidden" value="'.$maintaining.'" id="hidden-mbal_'.$row_branch['branch_id'].'">';
						
					echo '<tr>
							<td>'.$row_branch['branch_name'].'</td>
							<td><div id="txt_mbal"><span id="mbal_'.$row_branch['branch_id'].'">'.number_format($maintaining).'</span></div><div class="txt_en" id="bal_'.$row_branch['branch_id'].'"><span ><input type="number" id="mbalval_'.$row_branch['branch_id'].'" value="'.$maintaining.'" class="txtbox" onkeyup="c(this.id);"></span></div></td>
							<td><div id="txt_dis"><span id="expense_'.$row_branch['branch_id'].'">'.number_format($expense,2).'</span></div><div class="txt_en" id="ex_'.$row_branch['branch_id'].'"><span ><input type="number" id="exval_'.$row_branch['branch_id'].'" value="'.$expense.'" class="txtbox" onkeyup="c(this.id);"></span></div></td>
							<td><div id="reval_'.$row_branch['branch_id'].'">'.number_format($remaining,2).'</div></td>
							<td><div id="txt_disadd"><span id="addfund_'.$row_branch['branch_id'].'">'.number_format($add_fund,2).'</span></div><div class="txt_enadd" id="add_'.$row_branch['branch_id'].'"><span ><input type="number" id="addval_'.$row_branch['branch_id'].'" class="txtbox" value="'.$add_fund.'" onkeyup="c(this.id);"></span></div></td>
							<td><div id="totval_'.$row_branch['branch_id'].'">'.number_format($total_fund,2).'</div></td>
							<td><div id="totft_'.$row_branch['branch_id'].'">'.number_format($tansferred,2).'</div></td>
							<td><div class="disable" id="disbutton_'.$row_branch['branch_id'].'"><img src="images/ok.png" title="Save" width="20%" height="40%"> | <img src="images/cancel.png" title="Cancel" width="20%" height="40%"></div>
							<div class="enable" id="enbutton_'.$row_branch['branch_id'].'"><span id="ok_'.$row_branch['branch_id'].'"><img src="images/ok.png" title="Save" width="30%" height="50%"></span> | <span id="cancel_'.$row_branch['branch_id'].'"><img src="images/cancel.png" title="Cancel" width="30%" height="50%"></span></div>
							<div class="enable" id="enbutton1_'.$row_branch['branch_id'].'"><span id="okbal_'.$row_branch['branch_id'].'"><img src="images/ok.png" title="Save" width="30%" height="50%"></span> | <span id="cancelbal_'.$row_branch['branch_id'].'"><img src="images/cancel.png" title="Cancel" width="30%" height="50%"></span></div></td>
						</tr>';
				}
				echo '<input type="hidden" value="'.$ofr.'" id="ofr">';
			?>	
				<tr>
					<td style="background-color:#FFFF00;">Total</td>
					<td style="background-color:#FFFF00;"><div id="t_mbal"><?php echo number_format($tot_maintaining,2);?><div></td>
					<td style="background-color:#FFFF00;"><div id="t_ex"><?php echo number_format($tot_expense,2);?></div></td>
					<td style="background-color:#FFFF00;"><div id="t_reval"><?php echo number_format($tot_remaining,2);?></td>
					<td style="background-color:#FFFF00;"><div id="t_fundadd"><?php echo number_format($tot_fund,2);?></div></td>
					<td style="background-color:#FFFF00;"><div id="t_fund"><?php echo number_format($ofr,2);?></div></td>
					<td style="background-color:#FFFF00;">Total</td>
					<td style="background-color:#FFFF00;"></td>
				</tr>
				<tr>
					<td colspan="7"><center>Available Fund: <input type="number" class="fund_available" id="avbl_fund" value="<?php echo $row_av['amount'];?>" onKeyUp="c(this.id);" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Submit" class="submit" id="fund_available1" onClick="save();"></center></td>
				</tr>
				
				</table>
				<br />
				<br />
           </div>
			 
			  
            </div><!--.middle-->

            <footer class = "footer">
                <?php include 'template/footer.php';
                ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
	