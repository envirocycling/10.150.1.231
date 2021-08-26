<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['rpt_viewer_id'])) {
    echo "<script>location.replace('../../');</script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Envirocycling Fiber Inc.</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/ts_logo.png" />
        <link rel="stylesheet" href="cbFilter/cbCss.css" />
        <link rel="stylesheet" href="cbFilter/sup.css" />
        <link rel="stylesheet" href="cbFilter/mat.css" />
        <script src="cbFilter/jquery-1.8.3.js"></script>
        <script src="cbFilter/jquery-ui.js"></script>
        <script src="cbFilter/sup_combo.js"></script>
        <script src="cbFilter/mat_combo.js"></script>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>
		<script src="js/jquery-1.6.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery.ui.core.min.js"></script>
        <script src="js/setup.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
		<script type="text/javascript">
            $(document).ready(function () {
                setupLeftMenu();
                $('.datatable').dataTable();
                setSidebarHeight();
            });
        </script>
        <style>
            .table {
                border: 1px solid black;
                height: 500px;
                overflow: auto;
                width: 98%;
            }
            .button{
                width: 100%;
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
                <h2>EMPLOYEE ADVANCES LIST</h2>
				<br><br>
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
									
									$sql_data = mysql_query("SELECT * FROM employee_advances WHERE date>='$date_from' and date<='$date_to' and status LIKE '%".$_POST['status']."%' and approver='".$_SESSION['user_id']."'");
                               
                                } else {                             
                                     $sql_data = mysql_query("SELECT * FROM employee_advances WHERE status='pending' and approver='".$_SESSION['user_id']."'");
                                }

                                while ($rs_data = mysql_fetch_array($sql_data)) {
                            
								  $sql_employee = mysql_query("SELECT * from employee WHERE emp_id='".$rs_data['emp_id']."'");
								  $row_employee = mysql_fetch_array($sql_employee);
								  
								  $sql_user = mysql_query("SELECT * from users WHERE user_id='".$rs_data['approver']."'");
								  $row_user = mysql_fetch_array($sql_user);
								  
                                    echo '<tr>
												<td>'.date('Y/m/d' , strtotime($rs_data['date'])).'</td>
												<td>'.strtoupper($rs_data['ref_no']).'</td>
												<td>'.strtoupper($row_employee['name']).'</td>
												<td>'.strtoupper($rs_data['amount']).'</td>
												<td>'.strtoupper($rs_data['purpose']).'</td>
												<td>'.strtoupper($row_user['firstname']).', '.strtoupper($row_user['lastname']).'</td>
												<td>'.strtoupper($rs_data['status']).'</td>
												<td>';
													if($rs_data['status'] == 'liquidated'){
														echo '<a href="employee_advances_liquidated_form.php?ea_id='.$rs_data['ea_id'].'"><input type="button" value="View"  class="button"></a>';												}else{
														echo '<a href="employee_advances_view.php?ea_id='.$rs_data['ea_id'].'"><input type="button" value="View"  class="button"></a>'	;
													}
												 
													//if($rs_data['status'] == 'issued'){ echo '<a href="employee_advances_liquidated_form.php?ea_id='.$rs_data['ea_id'].'"><input type="button" class="button" value="Liquidated"></a>';} 
													echo '</td>
										</tr>';
                                }
                                ?>
                            </table>
							
                        </div>
                <br>
                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>