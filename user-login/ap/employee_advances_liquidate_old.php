
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
			
        </style>		
		<script>
			function f_button(id){
				var ctrl = Number(document.getElementById('tr_ctrl').value);
				
				if(id == 'add' && ctrl >= 15){
					alert("Please contact your System Admin.");
				}
				
				if(id == 'add' && ctrl < 15){
					var new_ctrl = (Number(document.getElementById('tr_ctrl').value) + 1);
						
						document.getElementById('tr_ctrl').value = new_ctrl;
						document.getElementById('tr_' + new_ctrl).hidden = false;
				}else if(id == 'minus' && ctrl!=0){
					var new_ctrl = (Number(document.getElementById('tr_ctrl').value) - 1);
						
						document.getElementById('tr_ctrl').value = new_ctrl;
						document.getElementById('tr_' + new_ctrl).hidden = true;
				}else if(id == 'submit'){
				
					var message = confirm("Do you want to proceed?.");
						
						if(message == true){
							var num =1;
							var num2 =1;
							var total_expense = $('#total_expense').html();
							var excess_cash = $('#excess_cash').html();
							var pcv_no = $('#pcv_no').val();
							var ea_id = $('#ea_id').val();
							var error = 'Required Field/s: ';
							var error_count = 0;
							
							while(num2 <= ctrl){
									var details = $('#details_' + num).val();
									var description = $('#description_' + num).val();
									var amount = $('#amount_' + num).val();
									if(details == 'OTHERS'){
											var specify = $('#specify_' + num).val();
										}
									if(details == 'OTHERS' && specify == '' ){
										var error = error + '(Specify)';
										var error_count = 1;
									}if(details == ''){
										var error = error + '(Details)';
										var error_count = 1;
									}if(amount == '' && amount <= 0 ){
										var error = error + '(Amount)';
										var error_count = 1;
									}if(pcv_no == ''){
										var error = error + '(PCV No)';
										var error_count = 1;
									}
								num2++;
							}
							
							if(error_count == 1){
								alert(error);
								return false;	
							}	
							
							while(num <= ctrl){
									var details = $('#details_' + num).val();
									var description = $('#description_' + num).val();
									var amount = $('#amount_' + num).val();
										if(details == 'OTHERS'){
											var specify = $('#specify_' + num).val();
										}
										
										var myData = 'ea_id=' + ea_id + '&total_expense=' + total_expense + '&excess_cash=' + excess_cash + '&details=' + details + '&specify=' + specify + '&amount=' + amount + '&description=' + description + '&pcv_no=' + pcv_no;
										
											$.ajax({
												 type: "POST",
												 url: "exec/employee_advances_liquidate_exec.php",
												 data: myData,
												 cache: false
											});
										
								num++;
							}
							alert("Successful.");
							location.replace("employee_advances_list.php");				
						}
												
				}
				
			}
		
		function f_compute(){
		
		//for computing expense
			var ctrl = Number(document.getElementById('tr_ctrl').value);
			var num = 1;
			var amount = 0;
				while(num <= ctrl){
						var amount2 = Number($('#amount_' + num).val());
						var amount = amount + amount2;
					num++;
				}
				$('#total_expense').html((amount).toFixed(2));
		//end computing expense	
			
			var total_expense = Number($('#total_expense').html());
			var cash_advance  = Number($('#cash_advance').html());
			
			var excess_cash = (cash_advance - total_expense).toFixed(2);
			
			$('#excess_cash').html(excess_cash);
		}
				
	</script>
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
				?>
                <br>

                <div align="center">
					<input type="hidden" value="1" id="tr_ctrl">
					<input type="hidden" value="<?php echo $_GET['ea_id'];?>" id="ea_id">
                    <h2>EMPLOYEE ADVANCES LIQUIDATE FORM</h2>
                    <br>
					
							<div class="table">
								<div class="row">
									<div class="column">Employee Name: <span class="text"><?php echo strtoupper($row_emp['emp_firstname'].', '. $row_emp['emp_lastname']);?></span></div>
									<div class="column">Date: <span class="text"><?php echo date('Y-m-d');?></span></div>
									<div class="column">PCV No. <input type="text" id="pcv_no" class="input3" required></div>
								</div>
								<div class="row">
									<div class="column">Branch: <span class="text"><?php echo strtoupper($_SESSION['branch']);?></span></div>
									<div class="column">Department: <span class="text"><?php echo strtoupper($row_emp['department']);?></span></div>
								</div>
								<div class="row">
									<div class="column2">Purpose: <span class="text"><?php echo strtoupper($row_eadv['purpose']);?></span></div>
								</div>
							</div>
							
							<br /><br />
							
							<div class="payTable" style="width: 70%;">
                    <table>
                        
						<tr>
                            <td>Details</td>
                            <td style="width:40%;">Desciption</td>
                            <td>Amount</td>
                        </tr>
						
						<tr class="input">
							<td>Cash Advance</td>
							<td></td>
							<td><span class="num" id="cash_advance"><?php echo $row_eadv['amount'];?></span></td>
						</tr>
					<?php
					$ctr = 1;
					$num = 16;
					while($ctr < $num){
						
						if($ctr > 1){
							$atrr = 'hidden';
						}
					?>
						<script>
						 $(document).ready(function () {
						 	var ctr = '<?php echo $ctr;?>';
							$('#details_' + ctr).select2();
							
							$('select').change(function(){
								var details = $('#details_' + ctr).val();
									if(details == 'OTHERS'){
										$('#specify_' + ctr ).show(100);
									}
							});
						});
					</script>
					
					<style>
						#details_<?php echo $ctr;?>{
							width:200px;
							text-align:center;
						}
						
					</style>
						<tr id="tr_<?php echo $ctr;?>" <?php echo $atrr;?>>
                            <td>
								<select name="details" id="details_<?php echo $ctr;?>" required>
												<option value="" selected >Please Select</option>
									<?php 
										$sql_details = mysql_query("SELECT * from employee_advances_details") or die(mysql_error());
										while($row_details = mysql_fetch_array($sql_details)){
											echo '<option value="'.$row_details['details'].'">'.strtoupper($row_details['details']).'</option>';
										}
											echo '<option value="OTHERS">OTHERS</option>';
									?>
								</select>
								<input type="text" class="input2" placeholder="PLEASE Specify" id="specify_<?php echo $ctr;?>" hidden>
							</td>
                            <td><input type="text" class="input" placeholder="OPTIONAL" id="description_<?php echo $ctr;?>"></td>
                            <td><input type="number" class="input2" id="amount_<?php echo $ctr;?>" onKeyUp="f_compute();"></td>
                        </tr>
					<?php
						$ctr++;
					}
					?>	
						<tr>
							<td colspan="3"><br /></td>
						</tr>
						<tr>
							<td>TOTAL EXPENSE</td>
							<td></td>
							<td><span class="num" id="total_expense">0.00</span></td>
						</tr>
						<tr>
							<td>EXCESS CASH</td>
							<td></td>
							<td><span class="num" id="excess_cash"><?php echo $row_eadv['amount'];?></span></td>
						</tr>
                    </table>
					</div>
						<div align="right" class="table"><button class="button" id="add" onClick="f_button(this.id)">+</button> | <button id="minus" class="button" onClick="f_button(this.id)">-</button></div>
				    <br>
                    <br><br>
					<div align="right" class="table"><button class="button2" id="submit" onClick="f_button(this.id)">Submit</button></div>
                </div>
                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>