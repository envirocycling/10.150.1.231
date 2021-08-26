
<!DOCTYPE html>
<html>
    <head>
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <title>Envirocycling Fiber Inc.</title>
        <script src="js/jquery.min.js" type="text/javascript"></script>
		<script src="js/select2.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <link rel="stylesheet" type="text/css" href="css/comment.css" />
		<link rel="stylesheet" type="text/css" href="css/liquidate.css" />
		<link rel="stylesheet" type="text/css" href="css/pay_table.css" />
	
        <style>
            .table{
                font-size: 18px;
            }
            .table td{
                padding: 3px;
            }
            #button2{
                width: 115px;
            }
            #prepaid{
                height: 20px;
                width: 20px;
            }#purpose{
                border-radius: 4px;
				width:450px;
				text-transform:uppercase;
				font-size:15px;
            }
			.note{
				font-size:12px;
				color:#FF0000;
				font-style:italic;
				font-weight:bold;
			}
			.large-submit{
    border-radius: 4px;
    height: 40px;
    width: 80px;
    font-size: 20px;
}
			
        </style>		
		
    </head>
    <body>
        <div class="wrapper">

            <header class="header">

                <?php
                include 'template/header.php';
				date_default_timezone_set("Asia/Singapore");
                ?>
            </header><!-- .header-->

            <div class="middle">

                <?php
                include 'template/menu.php';include 'config.php';
				
					$sql_eadv = mysql_query("SELECT * from employee_advances WHERE ea_id='".$_GET['ea_id']."'") or die(mysql_error());
					$row_eadv = mysql_fetch_array($sql_eadv);
				
					$sql_emp = mysql_query("SELECT * from employee WHERE emp_id='".$row_eadv['emp_id']."'") or die(mysql_error());
					$row_emp = mysql_fetch_array($sql_emp);
					
					$sql_liquidate = mysql_query("SELECT * from employee_advances_liquidate WHERE ea_id='".$_GET['ea_id']."'") or die(mysql_error());
				?>
                <br>

                <div align="center">
                    <h2>EMPLOYEE ADVANCES LIQUIDATE FORM</h2>
                    <br>
					
							<div class="table">
								<div class="row">
									<div class="column">Employee Name: <span class="text"><?php echo strtoupper($row_emp['name']);?></span></div>
									<div class="column">Date: <span class="text"><?php echo date('Y-m-d', strtotime($row_eadv['date_liquidated']));?></span></div>
									<div class="column">PCV No. <span class="text"><?php echo strtoupper($row_eadv['pcv_no']);?></span></div>
								</div>
								<div class="row">
									<div class="column">Branch: <span class="text"><?php echo "PAMPANGA";?></span></div>
									<div class="column">Department: <span class="text"><?php echo strtoupper($row_emp['department']);?></span></div>
								</div>
								<div class="row">
									<div class="column2">Purpose: <span class="text"><?php echo strtoupper($row_eadv['purpose']);?></span></div>
								</div>
							</div>
							
							<br /><br />
							
							<div class="payTable" style="width: 50%;">
                    <table>
                        
						<tr>
                            <td>Details</td>
                            <td>Amount</td>
                        </tr>
						
						<tr class="input">
							<td>Cash Advance</td>
							<td><span class="num" id="cash_advance"><?php echo number_format($row_eadv['amount'],2);?></span></td>
						</tr>
							<?php
								while($row_liquidate = mysql_fetch_array($sql_liquidate)){
									echo '<tr>
											<td>'.strtoupper($row_liquidate['details']).'</td>
											<td><span class="num">'.number_format($row_liquidate['amount'],0).'</span></td>
										</tr>';
								}
							?>
						<tr>
							<td colspan="2"><br /></td>
						</tr>
						<tr>
							<td>TOTAL EXPENSE</td>
							<td><span class="num" id="total_expense"><?php echo number_format($row_eadv['total_expense'],2);?></span></td>
						</tr>
						<tr>
							<td>EXCESS CASH</td>
							<td><span class="num" id="excess_cash"><?php echo number_format($row_eadv['excess_cash'],2);?></span></td>
						</tr>
                                                <tr>
							<td>CASH RETURNED</td>
							<td><span class="num" id="excess_cash"><?php echo number_format($row_eadv['returned_excess_cash'],2);?></span></td>
						</tr>
                    </table>
                </div>
                <br>
				<div align="right" style="width:70%;"><a href="employee_advancelist.php"><input type="button" value="Back" class="large-submit"></a></div><br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>