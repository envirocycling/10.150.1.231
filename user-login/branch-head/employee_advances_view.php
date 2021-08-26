<?php
@session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <title>Envirocycling Fiber Inc.</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/efi_ico.png" />

        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link href="css/adv_form.css" rel="stylesheet">
        
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
            }.large-submit2{
                border-radius: 4px;
                height: 40px;
                width: 110px;
                font-size: 20px;
            }.medium-select-3{
                text-transform: uppercase;
                border-radius: 4px;
                height: 30px;
                width: 350px;
                font-size: 18px;
            }
            .note{
				font-size:12px;
				color:#FF0000;
				font-style:italic;
				font-weight:bold;
			}

        </style>
        <script type="text/javascript">
            $(document).ready(function () {
                var ID = $('#emp').val().split("_");
                        var emp_id = ID[0];
                        var datax = 'emp_id' + emp_id;
                        if (emp_id == "") {
                            $("#ea_balance").html("");
                            return;
                        } else {
                            if (window.XMLHttpRequest) {
                                // code for IE7+, Firefox, Chrome, Opera, Safari
                                xmlhttp = new XMLHttpRequest();
                            } else {
                                // code for IE6, IE5
                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp.onreadystatechange = function () {
                                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                    $("#ea_balance").html(xmlhttp.responseText);
                                }
                            };
                            xmlhttp.open("GET", "exec/employee_advances_chkbalance.php?q=" + emp_id, true);
                            xmlhttp.send();
                        }
                
            });


			
	function textAreaAdjust(o) {
    	o.style.height = "1px";
    	o.style.height = (20+o.scrollHeight)+"px";
	}
	
	function datas(action){
		
		var message = confirm("Do you want to " + action + " ?");
		
		 if(message == true){
		 
			var ea_id = $("#ea_id").val();
			
			var myData = 'action=' + action + '&ea_id=' + ea_id;
				
				$.ajax({
					type: 'POST',
					url: 'exec/eadv_exec.php',
					data: myData,
				});

					alert("Successful");
					window.location.reload();
		}
	}
	
	function btn_back(){
		location.replace("employee_advances_list.php?view=1");
	}
	
        //start here	
            $(document).ready(function () {

                    /*var ID = $(this).attr('id');
                    if (ID == 'emp_id') {
                        var emp_id = $('#' + ID).val();
                        var datax = 'emp_id' + emp_id;
                        if (emp_id == "") {
                            $("#ea_balance").html("");
                            return;
                        } else {
                            if (window.XMLHttpRequest) {
                                // code for IE7+, Firefox, Chrome, Opera, Safari
                                xmlhttp = new XMLHttpRequest();
                            } else {
                                // code for IE6, IE5
                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp.onreadystatechange = function () {
                                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                    $("#ea_balance").html(xmlhttp.responseText);
                                }
                            };
                            xmlhttp.open("GET", "exec/employee_advances_chkbalance.php?q=" + emp_id, true);
                            xmlhttp.send();
                        }
                    }*/
                });

        </script>
    </head>
    <body onLoad="textAreaAdjust(purpose);">
        <div class="wrapper">

            <header class="header">

                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->

            <div class="middle">

                <?php
                include 'template/menu.php';include 'config.php';
					
					$sql_advances = mysql_query("SELECT * from employee_advances WHERE ea_id='".$_GET['ea_id']."'") or die(mysql_error());
					$row_advances = mysql_fetch_array($sql_advances);
					
					$sql_emp = mysql_query("SELECT * from employee WHERE emp_id='".$row_advances['emp_id']."'") or die(mysql_error());
					$row_emp = mysql_fetch_array($sql_emp);
					
					$sql_approver = mysql_query("SELECT * from users WHERE user_id='".$row_advances['approver']."'") or die(mysql_error());
					$row_approver = mysql_fetch_array($sql_approver);
                ?>
                <br>

                <div align="center">
                    <h2>EMPLOYEE ADVANCES FORM</h2>
                    <br>
					<input type="hidden" value="<?php echo $_GET['ea_id'];?>" id="ea_id">
                    <table class="table">
                            <tr>
                                <td>Company: </td>
                                <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo $row_advances['comp_id']; ?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td>Date: </td>
                                <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo $row_advances['date']; ?>" readonly></td>
								<td>Ref No: </td>
                                <td><input id="ref_no" class="medium-input" type="text" name="ref_no" value="<?php echo $row_advances['ref_no']; ?>" readonly>
                            </tr>
                            <tr>
                                <td>Employee Name:</td>
                                <td><input type="text" value="<?php echo $row_advances['emp_id'].'_'.$row_emp['name'];?>" class="medium-select-3" id="emp" readonly></td>
                            </tr>
                            <tr>
                                <td>Amount: </td>
                                <td><input id="amount" value="<?php echo $row_advances['amount'];?>" class="medium-input" type="number" name="" readonly></td>
                            </tr>
                                <td>Purpose: </td>
                                <td colspan="3"><textarea  style="overflow:hidden" name="purpose" id="purpose" readonly><?php echo @$row_advances['purpose'];?></textarea></td>
                            </tr>
                            <tr>
                                <td colspan="4"><div id="ea_balance"></div></td>
                            </tr>
							<tr>
                                <td><br><br>Approved By: </td>
                                <td valign="bottom"><?php if($row_advances['status'] == 'approved' || $row_advances['status'] == 'issued' || $row_advances['status'] == 'liquidated'){ echo '<img src="../../signatures_pamp/'.$row_approver['initial'].'.png" width="200px" height="100px">';}?><br><?php echo $row_approver['firstname'].', '.$row_approver['lastname'].' / '.$row_approver['initial'];?><font size="-2"><br><?php if($row_advances['status'] == 'approved' || $row_advances['status'] == 'liquidated' || $row_advances['status'] == 'issued'){ echo date('Y-m-d h:i A', strtotime($row_advances['date_time_approved']));}?></font></td>
                            </tr>
						<?php if($row_advances['status'] == 'issued'){ ?>
							<tr>
								<td class="note" colspan="4">Note: Cash advance already issued.</td>
							</tr>
						<?php }else if($row_advances['status'] == 'disapproved'){?>
							<tr>
								<td class="note" colspan="4">Cash advance already Disapproved.</td>
							</tr>
						<?php }?>
                            </tr>
                            <tr>
                                <td colspan="4" align="right"><?php if($row_advances['status'] == 'pending'){ ?><input id="approved" class="large-submit" type="button" value="Approve" onclick="datas(this.id);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="disapproved" class="large-submit2" type="submit" value="Disapprove" onclick="datas(this.id);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php }?><input type="button" value="Back" id="back" onclick="btn_back()" class="large-submit" ></td>
                            </tr>
                            </tr>
                        </table>
                    <br>
                    <br><br>
                </div>
                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>