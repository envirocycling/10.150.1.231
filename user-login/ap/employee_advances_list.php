
<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
         <script src="js/jquery.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/efi_ico.png" />
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
            });
        </script>

        <style>
            button{
                width: 70px;
            }
            .table{
                font-size: 11px;
            }
            #button{
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

                <div class="container">
                    <main class="content">
                       <div style="margin-left: -11px;" width="1200">
                            <?php
                            include 'template/menu.php';

                            ?>
                        </div>
                            <?php
                           
                include 'config.php'; ?>
                    <br>
                    <h2>EMPLOYEE ADVANCES</h2>
                    <br>
                    <form action="employee_advances_list.php" method="POST">
                        <?php
                        if (isset($_POST['submit'])) {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" readonly>';
                            echo 'To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" readonly>';
                            echo 'Status: <select name="status">
													<option value="' . $_POST['status'] . '">' . ucfirst($_POST['status']) . '</option>
													<option value="">All</option>
													<option value="pending">Pending</option>
													<option value="approved">Approved</option>
													<option value="disapproved">Disapproved</option>
													<option value="cancelled">Cancelled</option>
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
													<option value="disapproved">Disapproved</option>
													<option value="cancelled">Cancelled</option>
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
                                    <th class="data">Purpose</th>
                                    <th class="data">Amount</th>
                                    <th class="data">Status</th>
                                    <th class="data">PCV No.</th>
                                    <th class="data">Approver</th>
                                    <th class="data">Prepared By</th>
                                    <th class="data">Action</th>
                                </tr>
                            </thead>
                            <?php

							if (!isset($_GET['for_process'])){
								$for_process = "";
							} else {
								$for_process = $_GET['for_process'];
							}
                            
                            if (isset($_POST['submit'])) {
                                $date_from = str_replace('/', '-', $_POST['from']);
                                $date_to = str_replace('/', '-', $_POST['to']);

                                $sql_data = mysql_query("SELECT * FROM employee_advances WHERE date>='$date_from' and date<='$date_to' and status LIKE '%" . $_POST['status'] . "%' and branch_id='7'");
                            } else if($for_process == 1){
                                $sql_data = mysql_query("SELECT * FROM employee_advances WHERE status='approved' and branch_id='7'");
                            } else {
                                $sql_data = mysql_query("SELECT * FROM employee_advances WHERE date='" . date('Y-m-d') . "' and branch_id='7'");
                            }

                            while ($rs_data = mysql_fetch_array($sql_data)) {

                                $sql_employee = mysql_query("SELECT * from employee WHERE emp_id='" . $rs_data['emp_id'] . "'");
                                $row_employee = mysql_fetch_array($sql_employee);

                                $sql_user = mysql_query("SELECT * from users WHERE user_id='" . $rs_data['approver'] . "'");
                                $row_user = mysql_fetch_array($sql_user);
                                
                                $sql_prepare = mysql_query("SELECT * from users WHERE user_id='" . $rs_data['prepared_by'] . "'");
                                $row_prepare= mysql_fetch_array($sql_prepare);
                                
                                /*$date_liquidated_chk = strtotime($rs_data['date_liquidated']);
                                $date_issued_chk = strtotime($rs_data['date_received']);
                                
                                if(!empty($date_liquidated_chk)){
                                    $date_liquidated = date('Y/m/d', strtotime($rs_data['date_liquidated']));
                                }else{
                                    $date_liquidated = '';
                                }
                                
                                if(!empty($date_issued_chk)){
                                    $date_issued = date('Y/m/d', strtotime($rs_data['date_received']));
                                }else{
                                    $date_issued = '';
                                }
                                                               

                                if($rs_data['returned_excess_cash'] == 1){
                                    $liquidated = $rs_data['total_expense'] + $rs_data['excess_cash'];
                                }else{
                                    $liquidated = $rs_data['total_expense'] - $rs_data['excess_cash'];
                                }
                                
                                $balance = $rs_data['amount'] - $liquidated;
                                */
                                echo '<tr>
					<td>' . date('Y/m/d', strtotime($rs_data['date'])) . '</td>
					<td>' . strtoupper($rs_data['ref_no']) . '</td>
                                        <td>' . strtoupper($row_employee['name']) . '</td>
					<td>' . strtoupper($rs_data['purpose']) . '</td>
					<td>' . number_format($rs_data['amount']) . '</td>
					<td>' . strtoupper($rs_data['status']) . '</td>
                                        <td>' . strtoupper($rs_data['pcv_no']) . '</td>  
					<td>' . strtoupper($row_user['firstname']) . ', ' . strtoupper($row_user['lastname']) . '</td>
                                        <td>' . strtoupper($row_prepare['firstname']) . ', ' . strtoupper($row_prepare['lastname']) . '</td>
					<td>';
                                if ($rs_data['status'] == 'liquidated') {
                                    echo '<a href="employee_advances_liquidated_form.php?ea_id=' . $rs_data['ea_id'] . '"><input type="button" id="button" value="View"></a>';
                                } else {
                                    echo '<a href="employee_advances_view.php?ea_id=' . $rs_data['ea_id'] . '"><input type="button" id="button" value="View"></a>';
                                }
                                if ($rs_data['status'] == 'pending') {
                                    echo '<a href="employee_advances_edit.php?ea_id=' . $rs_data['ea_id'] . '"><input type="button" id="button" value="Edit"></a>';
                                }

                                if ($rs_data['status'] == 'issued') {
                                    echo '<a href="employee_advances_liquidate.php?ea_id=' . $rs_data['ea_id'] . '"><input type="button" id="button" value="Liquidate"></a>';
                                }
                                echo '</td>
										</tr>';
                            }
                            ?>
                        </table>

                     </main><!-- .content -->
                </div><!-- .container-->

                <aside class="left-sidebar">
                    <iframe src="template/pending2.php" width="367" height="600" scrolling="yes"></iframe>
                </aside><!-- .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
