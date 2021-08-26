<?php
session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';
if (!isset($_SESSION['bh_id'])) {
    echo "<script>location.replace('../../');</script>";
}
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
        <script src="js/pending.js" type="text/javascript"></script>
        <script src="js/pending_outgoing.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link rel="stylesheet" type="text/css" href="css/adv_form.css" />
        <link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
        <script src="js/jquery-1.6.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery.ui.core.min.js"></script>
        <script src="js/setup.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                setupLeftMenu();
                $('.datatable').dataTable();
                setSidebarHeight();
                
                $('.button').click(function(){
                    var con = confirm('Do you want to cancel this request?');
                    
                    if(con == true){
                        var id = $(this).attr('id');
                        var dataX = 'ea_id=' + id + '&action=cancelled';

                            $.ajax({
                                type: 'POST',
                                url: 'exec/eadv_exec.php',
                                data: dataX,
                                success: function(){
                                    alert('Successful');
                                    window.location.reload();
                                    location.reload();
                                }
                            });
                    }else{
                        return false;
                    }
                });
            });
        </script>
        <style>
            .table {
                border: 1px solid black;
                height: 500px;
                overflow: auto;
                width: 1180px;
            }
            button{
                width: 70px;
            }
            #button{
                width: 100%;
            }
            td{
                padding-bottom: 15px;
            }
        </style>
    </head>
    <body>

        <div class="wrapper">

            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->
            <div class="middle">
                <?php
                include 'template/menu.php';
                ?>
                <br>
                <br>
                <div style="margin-left: 10px;"> 
                    <h2>EMPLOYEE ADVANCES</h2>
                        <br>
                        <form action="employee_advances_list.php" method="POST">
                            <?php
       
                            if (isset($_POST['submit'])) {
                                echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" readonly>';
                                echo 'To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" readonly>';
								echo 'Status: <select name="status">
													<option value="'.$_POST['status'].'">'.ucfirst($_POST['status']).'</option>
													<option value="">All</option>
													<option value="pending">Pending</option>
													<option value="approved">Approved</option>
													<option value="issued">Issued</option>
													<option value="liquidated">Liquidated</option>
											</select>';
                            } else {
                                echo 'From: <input class = "tcal" type = "text" name = "from" value = "' . date('Y/m/d') . '" size = "10" readonly>';
                                echo 'To: <input type = "text" class = "tcal" name = "to" value = "' . date('Y/m/d') . '" size = "10" readonly>';
								echo 'Status: <select name="status" required>
													<option value="" selected disabled>Select</option>
													<option value="">All</option>
													<option value="pending">Pending</option>
													<option value="approved">Approved</option>
													<option value="issued">Issued</option>
													<option value="liquidated">Liquidated</option>
											</select>';
                            }
							
                                echo ' <input class="small-submit" type = "submit" name = "submit" value = "Submit">';
                            ?>
                        </form>
                        <br>
                        <div class="table">
                            <table class="data display datatable" id="example">
                                <thead>
                                    <tr class="data">
                                        <th class="data">Date</th>
                                        <th class="data">Ref No.</th>
                                        <th class="data">PCV No.</th>
                                        <th class="data">Employee Name</th>
                                        <th class="data">Amount</th>
                                        <th class="data">Purpose</th>
                                        <th class="data">Approver</th>
                                        <th class="data">Status</th>
					<th class="data">Action</th>
                                    </tr>
                                </thead>
                                <?php
                                if (isset($_POST['submit'])) {
                                    $date_from = str_replace('/', '-', $_POST['from']);
                                    $date_to = str_replace('/', '-', $_POST['to']);
									
									$sql_data = mysql_query("SELECT * FROM employee_advances WHERE date>='$date_from' and date<='$date_to' and status LIKE '%".$_POST['status']."%' and  approver='".$_SESSION['user_id']."' and branch_id='7'");
                               
                                } else {                             
                                     $sql_data = mysql_query("SELECT * FROM employee_advances WHERE status='pending' and approver='".$_SESSION['user_id']."' and branch_id='7'");
                                }

                                while ($rs_data = mysql_fetch_array($sql_data)) {
                            
								  $sql_employee = mysql_query("SELECT * from employee WHERE emp_id='".$rs_data['emp_id']."'");
								  $row_employee = mysql_fetch_array($sql_employee);
								  
								  $sql_user = mysql_query("SELECT * from users WHERE user_id='".$rs_data['approver']."'");
								  $row_user = mysql_fetch_array($sql_user);
								  
                                    echo '<tr>
												<td>'.date('Y/m/d' , strtotime($rs_data['date'])).'</td>
												<td>'.strtoupper($rs_data['ref_no']).'</td>
                                                                                                    <td>'.strtoupper($rs_data['pcv_no']).'</td>
												<td>'.strtoupper($row_employee['name']).'</td>
												<td>'.strtoupper($rs_data['amount']).'</td>
												<td>'.strtoupper($rs_data['purpose']).'</td>
												<td>'.strtoupper($row_user['firstname']).', '.strtoupper($row_user['lastname']).'</td>
												<td>'.strtoupper($rs_data['status']).'</td>
												<td>';
													if($rs_data['status'] == 'liquidated'){
														echo '<a href="employee_advances_liquidated_form.php?ea_id='.$rs_data['ea_id'].'"><input type="button" id="button"  value="View"></a>';												}else{
														echo '<a href="employee_advances_view.php?ea_id='.$rs_data['ea_id'].'"><input type="button" id="button"  value="View"></a>'	;
													}
                                                                                                        if($rs_data['status'] == 'approved'){
                                                                                                                echo "<input type='button' value='Cancel' id='".$rs_data['ea_id']."' class='button'>";
                                                                                                        }
												 
													 
													echo '</td>
										</tr>';
                                }
                                ?>
                            </table>
                    </div>
                </div>

                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>