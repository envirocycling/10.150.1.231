<?php
session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';
if (!isset($_SESSION['gm_id'])) {
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
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <script src="js/pending.js" type="text/javascript"></script>
        <script src="js/pending_outgoing.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
        <script src="js/jquery-1.6.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery.ui.core.min.js"></script>
        <script src="js/setup.js" type="text/javascript"></script>
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
                width: 1180px;
            }
            button{
                width: 70px;
            }
            #button{
                width: 100%;
            }
            .submit{
                height: 20px;
                width: 80px;
                font-size: 12px;
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
                        <form action="employee_advancelist.php" method="POST">
                            <?php
                            if (isset($_POST['branch_id'])) {
                            $branch_id = $_POST['branch_id'];
                        } else if (isset($_GET['branch_id'])) {
                            $branch_id = $_GET['branch_id'];
                        } else {
                            // $sql_branch_id = mysql_query("SELECT * FROM company");
                            // $rs_branch_id = mysql_fetch_array($sql_branch_id);
                            // $branch_id = $rs_branch_id['branch_id'];
							$branch_id = "";
                        }
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
                                                                echo 'Branch: <select name="branch_id">';
							if ($branch_id == ""){
								echo '<option value="">All Branch</option>';
							} else {
								$sql = mysql_query("SELECT * FROM branches WHERE branch_id='$branch_id'");
								$rs = mysql_fetch_array($sql);
								echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
								echo '<option value="">All Branch</option>';
							}
                            $sql = mysql_query("SELECT * FROM branches WHERE branch_id!='$branch_id'");
                            while ($rs = mysql_fetch_array($sql)) {
                                echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
                            }
                            echo '</select>';
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
                                                                echo 'Branch: <select name="branch_id">';
                            if ($branch_id == ""){
								echo '<option value="">All Branch</option>';
							} else {
								$sql = mysql_query("SELECT * FROM branches WHERE branch_id='$branch_id'");
								$rs = mysql_fetch_array($sql);
								echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
								echo '<option value="">All Branch</option>';
							}
                            $sql = mysql_query("SELECT * FROM branches WHERE branch_id!='$branch_id'");
                            while ($rs = mysql_fetch_array($sql)) {
                                echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
                            }
                            echo '</select>';
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
                                        <th class="data">Branch</th>
                                        <th class="data">Ref No.</th>
                                        <th class="data">PCV No.</th>
                                        <th class="data">Employee Name</th>
                                        <th class="data">Amount</th>
                                        <th class="data">Purpose</th>
                                        <th class="data">Prepared By</th>
                                        <th class="data">Status</th>
					<th class="data">Action</th>
                                    </tr>
                                </thead>
                                <?php
                                if (isset($_POST['submit'])) {
                                    $date_from = str_replace('/', '-', $_POST['from']);
                                    $date_to = str_replace('/', '-', $_POST['to']);
									
									$sql_data = mysql_query("SELECT * FROM employee_advances WHERE date>='$date_from' and date<='$date_to' and status LIKE '%".$_POST['status']."%' and  approver LIKE '".$_SESSION['user_id']."-%' or approver = '".$_SESSION['user_id']."') and branch_id LIKE '%".$branch_id."%'");
                               
                                } else {                             
                                     $sql_data = mysql_query("SELECT * FROM employee_advances WHERE status='pending' and (approver LIKE '".$_SESSION['user_id']."-%' or approver = '".$_SESSION['user_id']."')");
                                }

                                while ($rs_data = mysql_fetch_array($sql_data)) {
                            
								  $sql_employee = mysql_query("SELECT * from employee WHERE emp_id='".$rs_data['emp_id']."'");
								  $row_employee = mysql_fetch_array($sql_employee);
								  
								  $sql_user = mysql_query("SELECT * from users WHERE user_id='".$rs_data['approver']."'");
								  $row_user = mysql_fetch_array($sql_user);
                                                                  
                                                                  
                                $sql_branch = mysql_query("SELECT * FROM branches WHERE branch_id='" . $rs_data ['branch_id'] . "'");
                                $rs_branch = mysql_fetch_array($sql_branch);
                                
                            $sql_approver = mysql_query("SELECT * from users WHERE user_id = '".$rs_data['approver']."'") or die(mysql_error());
                            $row_approver = mysql_fetch_array($sql_approver);
                            
                            $sql_prepapredby = mysql_query("SELECT * from users WHERE user_id = '".$rs_data['prepared_by']."'") or die(mysql_error());
                            $row_preparedby = mysql_fetch_array($sql_prepapredby);
                            
                            $approver = ucwords($row_approver['firstname']).', '.ucwords($row_approver['lastname']);
                            $preparedby = ucwords($row_preparedby['firstname']).', '.ucwords($row_preparedby['lastname']);   
                                
                            $approver_expleode = explode("-",$rs_data['approver']);
                            $prepared_expleode = explode("-",$rs_data['prepared_by']);
                            if(!empty($approver_expleode[1])){
                                $approver = $approver_expleode[1];
                            }if(!empty($prepared_expleode[1])){
                                $preparedby = $approver_expleode[1];
                            }				  
                                    echo '<tr>
												<td>'.date('Y/m/d' , strtotime($rs_data['date'])).'</td>
                                                                                                    <td>'.$rs_branch['branch_name'].'</td>
												<td>'.strtoupper($rs_data['ref_no']).'</td>
                                                                                                    <td>'.strtoupper($rs_data['pcv_no']).'</td>
												<td>'.strtoupper($row_employee['name']).'</td>
												<td>'.number_format($rs_data['amount']).'</td>
												<td>'.strtoupper($rs_data['purpose']).'</td>
                                                                                                <td class="data">'.$preparedby.'</td>
												<td>'.strtoupper($rs_data['status']).'</td>
												<td>';
													if($rs_data['status'] == 'liquidated'){
														echo '<a href="employee_advances_liquidated_form.php?ea_id='.$rs_data['ea_id'].'"><input type="button" id="button"  value="View"></a>';												}else{
														echo '<a href="employee_advances_view.php?ea_id='.$rs_data['ea_id'].'"><input type="button" id="button"  value="View"></a>'	;
													}
												 
													//if($rs_data['status'] == 'issued'){ echo '<a href="employee_advances_liquidate.php?ea_id='.$rs_data['ea_id'].'"><input type="button" id="button" value="Liquidate"></a>';} 
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