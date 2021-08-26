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
                    <h2>TRUCKMONITORING PENALTY</h2>
                    <br>
                    <form action="truck_monitoring.php" method="POST">
                        <?php
                        if (isset($_POST['branch_id'])) {
                            $branch_id = $_POST['branch_id'];
                        } else if (isset($_GET['branch_id'])) {
                            $branch_id = $_GET['branch_id'];
                        } else {
                            $sql_branch_id = mysql_query("SELECT * FROM company");
                            $rs_branch_id = mysql_fetch_array($sql_branch_id);
                            $branch_id = $rs_branch_id['branch_id'];
                        }

                        if (isset($_POST['from'])) {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" readonly>';
                            echo 'To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" readonly>';
                            echo 'Branch: <select name="branch_id">';
                            $sql = mysql_query("SELECT * FROM branches WHERE branch_id='$branch_id'");
                            $rs = mysql_fetch_array($sql);
                            echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
                            $sql = mysql_query("SELECT * FROM branches WHERE branch_id!='$branch_id'");
                            while ($rs = mysql_fetch_array($sql)) {
                                echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
                            }
                            echo '</select>';
                            echo ' <input class = "submit" type = "submit" name = "submit" value = "Submit">';
                        } else {
                            echo 'From: <input class = "tcal" type = "text" name = "from" value = "' . date('Y/m/d') . '" size = "10" readonly>';
                            echo 'To: <input type = "text" class = "tcal" name = "to" value = "' . date('Y/m/d') . '" size = "10" readonly>';
                            echo 'Branch: <select name="branch_id">';
                            $sql = mysql_query("SELECT * FROM branches WHERE branch_id='$branch_id'");
                            $rs = mysql_fetch_array($sql);
                            echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
                            $sql = mysql_query("SELECT * FROM branches WHERE branch_id!='$branch_id'");
                            while ($rs = mysql_fetch_array($sql)) {
                                echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
                            }
                            echo '</select>';
                            echo ' <input class ="submit" type = "submit" name = "submit" value = "Submit">';
                        }
                        ?>
                    </form>
                    <br>
                    <div class="table">
                        <table class="data display datatable" id="example">
                            <thead>
                                <tr class="data">
                                    <th class="data">Date</th>
                                    <th class="data">Supplier Name</th>
									<th class="data">Plate No</th>
                                    <th class="data">Branch</th>
                                    <th class="data">Amount</th>
                                    <th class="data">Status</th>
                                    <th class="data">Remarks</th>
                                    <th class="data">Action</th>
                                </tr>
                            </thead>
                            <?php
                            if (isset($_POST['from'])) {
                                $date_from = str_replace('/', '-', $_POST['from']);
                                $date_to = str_replace('/', '-', $_POST['to']) . " 23:59:59";
                                $sql_data = mysql_query("SELECT * FROM truck_penalty_reqremove WHERE branch_id='$branch_id' and date>='$date_from' and date<='$date_to' and status!='cancelled'");
                            } else {
                                $sql_data = mysql_query("SELECT * FROM truck_penalty_reqremove WHERE branch_id='$branch_id' and status=''");
                            }

                            while ($rs_data = mysql_fetch_array($sql_data)) {
                                $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_data['supplier_id'] . "'");
                                $rs_sup = mysql_fetch_array($sql_sup);
                                $sql_pty = mysql_query("SELECT * FROM adv_paytype WHERE acpty_id='" . $rs_data['acpty_id'] . "'");
                                $rs_pty = mysql_fetch_array($sql_pty);
                                $sql_ty = mysql_query("SELECT * FROM adv_type WHERE acty_id='" . $rs_data['acty_id'] . "'");
                                $rs_ty = mysql_fetch_array($sql_ty);
                                $sql_branch = mysql_query("SELECT * FROM branches WHERE branch_id='" . $rs_data ['branch_id'] . "'");
                                $rs_branch = mysql_fetch_array($sql_branch);
								
								if(empty($rs_data['status'])){
									$status = 'PENDING';
								}else{
									$status = strtoupper($rs_data['status']);
								}
								
                                echo "<tr>";
                                echo "<td>" . date("Y/m/d", strtotime($rs_data['date'])) . "</td>";
                                echo "<td>" . $rs_sup ['supplier_id'] . "_" . $rs_sup ['supplier_name'] . "</td>";
								echo "<td>" . $rs_data ['plate_number'] . "</td>";
                                echo "<td>" . $rs_branch ['branch_name'] . "</td>";
                                echo "<td>" . $rs_data['amount'] . "</td>";
                                echo "<td>" . $status . "</td>";
								echo "<td>" . strtoupper($rs_data['remarks']) . "</td>";
                                echo "<td><a href='truck_monitoring_view.php?tpr_id=" . $rs_data['tpr_id'] . "'><button>View</button></a></td>";
                                echo "</tr>";
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