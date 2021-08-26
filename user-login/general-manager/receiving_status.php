<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
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
        <link rel="shortcut icon" href="images/ts_logo.png" />
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

            function openWindow(str) {
                window.open("edit_receiving.php?trans_id=" + str, 'mywindow', 'width=1020,height=600,left=150,top=20');
            }

            function openWindow3(str) {
                window.open("view_rec_trans_details.php?trans_id=" + str, 'mywindow', 'width=900,height=500,left=180,top=20');
            }
        </script>
        <style>
            .table {
                border: 1px solid black;
                height: 500px;
                overflow: auto;
                width: 1180px;
            }
            .submit{
                height: 20px;
                width: 80px;
                font-size: 12px;
            }
            .button{
                height: 20px;
                width: 60px;
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
                <div style="margin-left: 10px;">
                    <h2>Receiving Status</h2>
                    <br>
                    <form action="" method="POST">
                        <?php
                        if (isset($_POST['from'])) {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" required> ';
                            echo 'To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" required> ';
                            echo 'Status: <select name="status">';
                            if ($_POST['status'] != '') {
                                echo '<option value="' . $_POST['status'] . '">' . ucfirst($_POST['status']) . '</option>';
                            }
                            echo '<option value="">All</option>';
                            echo '<option value="pending">Pending</option>';
                            echo '<option value="for_evaluation">For Evaluation</option>';
                            echo '</select>';
                            echo '<input class="submit" type="submit" name="submit" value="Submit">';
                        } else {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . date('Y/m/d') . '" size="10" required> ';
                            echo 'To: <input type="text" class="tcal" name="to" value="' . date('Y/m/d') . '" size="10" required> ';
                            echo 'Status: <select name="status">';
                            echo '<option value="">All</option>';
                            echo '<option value="pending">Pending</option>';
                            echo '<option value="for_evaluation">For Evaluation</option>';
                            echo '</select>';
                            echo '<input class="submit" type="submit" name="submit" value="Submit">';
                        }
                        ?>
                    </form>
                    <br>
                    <div class="table">
                        <?php
                        $total_weight = 0;
                        $total_less_weight = 0;
                        $corrected_weight = 0;
                        echo '<table class="data display datatable" id="example">
                        <thead>
                        <tr class="data">
                                    <th class="data" width="40">Date</th>
                                    <th class="data" width="80">STR #</th>
                                    <th class="data" width="80">Supplier Name</th>
                                    <th class="data">Plate #</th>            
                                    <th class="data">Delivered To</th>
                                    <th class="data">Branch</th>
                                    <th class="data">Status</th>
                                    <th class="data">Action</th>
                                </tr>
                                </thead>';
                        if (!isset($_POST['from'])) {
                            $sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE (status='' or status='for_evaluation') and date>='2015/10/02'");
                        } else {
                            if ($_POST['status'] == '') {
                                $sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE (status='' or status='for_evaluation') and date>='" . $_POST['from'] . "' and date<='" . $_POST['to'] . "'");
                            } else {
                                if ($_POST['status'] == 'pending') {
                                    $status = '';
                                } else {
                                    $status = $_POST['status'];
                                }
                                $sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE status='$status' and date>='" . $_POST['from'] . "' and date<='" . $_POST['to'] . "'");
                            }
                        }
                        while ($rs_rec = mysql_fetch_array($sql_rec)) {
                            $sql_count = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='" . $rs_rec['trans_id'] . "'");
                            $rs_count = mysql_num_rows($sql_count);

                            $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_rec['supplier_id'] . "'");
                            $rs_sup = mysql_fetch_array($sql_sup);

                            $sql_dt = mysql_query("SELECT * FROM delivered_to WHERE dt_id='" . $rs_rec['dt_id'] . "'");
                            $rs_dt = mysql_fetch_array($sql_dt);
                            echo "<tr class='data'>";
                            echo "<td class='data'>" . $rs_rec['date'] . "</td>";
                            echo "<td class='data'>" . $rs_rec['str_no'] . "</td>";
                            echo "<td class='data'>" . $rs_sup['supplier_id'] . "_" . strtoupper($rs_sup['supplier_name']) . "</td>";
                            echo "<td class='data'>" . strtoupper($rs_rec['plate_number']) . "</td>";
                            echo "<td class='data'>" . strtoupper($rs_dt['name']) . "</td>";
                            echo "<td class='data'>PAMPANGA</td>";
                            if ($rs_rec['status'] == '') {
                                echo "<td class='data'>PENDING</td>";
                            } else {
                                echo "<td class='data'>" . strtoupper($rs_rec['status']) . "</td>";
                            }
                            echo "<td class='data'>";
                            echo "<button id='" . $rs_rec['trans_id'] . "' onclick='openWindow(this.id);' class='button'>Edit</button>";
                            echo "<button id='" . $rs_rec['trans_id'] . "' onclick='openWindow3(this.id);' class='button'>View</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                        </table>
                    </div>
                </div>
            </div><!--.middle-->

            <footer class = "footer">
                <?php include 'template/footer.php';
                ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>