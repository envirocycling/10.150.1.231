<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';

if(isset($_POST['submit'])){
					$url = 'http://paymentsystem.efi.net.ph/new_digi_payment.php';
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_NOBODY, true);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_exec($ch);
					$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close($ch);

						if (200 == $retcode) {
								echo '<form action="'.$url.'" method="post" name="myForm">
											<input type="hidden" value="'.$_POST['date'].'" name="date">
									   </form>';
								echo '<script>
										document.myForm.submit();
									</script>';
						}else{
							echo '<script>
									alert("Server Connection is Down. Please Try Again Later.");
								</script>';
						}
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
		<link rel="stylesheet" type="text/css" href="css/digi_payment_tbl.css" />
		<script type="text/javascript" src="js/fund_process2.js"></script>
    </head>

    <body>
        <div class="wrapper">
            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->

            <div class="middle" align="center">

                <?php
                include 'template/menu.php';
				echo '<br /><br /><br />';
					if(empty($_POST['myDate'])){
					$date2 = date('F d, Y');
					$myDate = date('Y/m/d');
				}else{
					$date2 = date('F d, Y' , strtotime($_POST['myDate']));
					$myDate = $_POST['myDate'];
				}
				
                ?>
				<form action="" method="post">
					<div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date: <input class="tcal" type="text" name="date" value="<?php echo $myDate;?>" size="10" id="myDate" required>&nbsp;&nbsp;&nbsp; <input type="submit" name="submit" style="width:100px; height:25px;"></div>
				   </form>
		<?php if($_POST['pro'] == '1'){	 ?>
                <br><br>
                <h2>DIGI PAYMENT</h2>
				<h4><?php echo $date2;?></h4>
<?php }?>
                <br>
                <br> <br>		
			<div class="frm_limit">
			<?php if($_POST['pro'] == '1'){	 ?>
				<table class="digi_payment_tbl">
					<tr>
						<td>BRANCH</td>
						<td>BRANCH DIGI</td>
						<td>BRANCH AMOUNT</td>
						<td>PAMPANGA DIGI</td>
						<td>PAMPANGA AMOUNT</td>
					</tr>
			<?php
			
			$sql_branch = mysql_query("SELECT * from branches WHERE branch_id !='7' and branch_id !='10'") or die(mysql_error()); 
						while($row_branch = mysql_fetch_array($sql_branch)){
						$branch = strtoupper($row_branch['branch_name']);
						
						$pamp_val = explode("-",$_POST[$branch]);
				
				//CHECK THE CONENCTION
					$url = 'http://'.$row_branch['ip_address'].'/ts/';
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_NOBODY, true);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_exec($ch);
					$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close($ch);

						if (200 == $retcode) {
							$conn2 = mysqli_connect(''.$row_branch['ip_address'].'', 'efi', 'enviro101', 'truck_scale');
   							$sql_digi = mysqli_query($conn2, "SELECT count(payment_id) as num_rows, sum(grand_total) as total FROM payment WHERE bank_code LIKE '%SBC%' and date='$myDate' and status!='deleted' and status!='cancelled' and status!='deleted'");
							$row_digi_num = mysqli_fetch_array($sql_digi);
							$nums[$branch] = $row_digi_num['num_rows'];
							$total[$branch] = $row_digi_num['total'];
						}else{
							$nums[$branch] = 'Not Connected';
						}
						if(empty($pamp_val[2])){
							$pamp_amount = '0';
						}else{
							$pamp_amount = $pamp_val[2];
						}
							echo '<tr>';
							echo '<td>'.strtoupper($row_branch['branch_name']).'</td>';
							echo '<td>'.$nums[$branch].'</td>';
							echo '<td> Php '.number_format($total[$branch],2).'</td>';
							echo '<td>'.$pamp_val[1].'</td>';
							echo '<td>Php '.number_format($pamp_amount,2).'</td>';
							echo '</tr>';
						
						$branch_total_trans += $nums[$branch];
						$branch_total_amount += $total[$branch];
						$pamp_total_trans += $pamp_val[1];
						$pamp_total_amount += $pamp_amount;
						
						}
					?>
					<tr style="background-color:yellow;">
						<td>TOTAL</td>
						<td><?php echo $branch_total_trans;?></td>
						<td><?php echo 'Php '.number_format($branch_total_amount,2);?></td>
						<td><?php echo $pamp_total_trans;?></td>
						<td><?php echo 'Php '.number_format($pamp_total_amount,2);?></td>
					</tr>
				</table>
	<?php }else{ echo '<h2>No Date Selected</h2>';}?>
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
	