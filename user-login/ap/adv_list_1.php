<?php
@session_start();
date_default_timezone_set("Asia/Singapore");
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
        <link href="css/adv_form.css" rel="stylesheet">

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
            function cancel(id) {
                var r = confirm("Are you sure you want to cancel this request?");
                if (r === true) {
                    var data = 'ac_id=' + id;
                    $.ajax({
                        url: "exec/adv_exec.php?action=cancelAc",
                        type: 'POST',
                        data: data
                    }).done(function () {
                        alert('Successfully Cancelled.');
                    });
                    $("#" + id).hide();
                }
            }

            function print(id) {
                window.open("adv_print.php?ac_id=" + id, 'mywindow', 'width=1020,height=600,left=150,top=20');
            }
        </script>

        <style>
            .table {
                border: 1px solid black;
                height: 500px;
                overflow: auto;
                width: 810px;
            }
            button{
                width: 70px;
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
                        <br>
                        <h2>ADVANCES</h2>
                        <br>
                        <form action="adv_list.php" method="POST">
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
                                echo 'Status: <select name="status">';
                                if ($_POST['status'] != '') {
                                    echo '<option value="' . $_POST['status'] . '">' . ucfirst($_POST['status']) . '</option>';
                                }
                                echo '<option value="">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="verified">Verified</option>
                                    <option value="approved">Approved</option>
                                    <option value="issued">Issued</option>
                                    <option value="paid">Paid</option>
                                    <option value="cancelled">Cancelled</option>
                                    </select> ';
                                echo 'Branch: <select name="branch_id">';
                                $sql = mysql_query("SELECT * FROM branches WHERE branch_id='$branch_id'");
                                $rs = mysql_fetch_array($sql);
                                echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
                                $sql = mysql_query("SELECT * FROM branches WHERE branch_id!='$branch_id'");
                                while ($rs = mysql_fetch_array($sql)) {
                                    echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
                                }
                                echo '</select>';
                                echo ' <input class="small-submit" type = "submit" name = "submit" value = "Submit">';
                            } else {
                                echo 'From: <input class = "tcal" type = "text" name = "from" value = "' . date('Y/m/d') . '" size = "10" readonly>';
                                echo 'To: <input type = "text" class = "tcal" name = "to" value = "' . date('Y/m/d') . '" size = "10" readonly>';
                                echo 'Status: <select name="status">';
                                echo '<option value="">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="verified">Verified</option>
                                    <option value="approved">Approved</option>
                                    <option value="issued">Issued</option>
                                    <option value="paid">Paid</option>
                                    <option value="cancelled">Cancelled</option>
                                    </select> ';
                                echo 'Branch: <select name="branch_id">';
                                $sql = mysql_query("SELECT * FROM branches WHERE branch_id='$branch_id'");
                                $rs = mysql_fetch_array($sql);
                                echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
                                $sql = mysql_query("SELECT * FROM branches WHERE branch_id!='$branch_id'");
                                while ($rs = mysql_fetch_array($sql)) {
                                    echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
                                }
                                echo '</select>';
                                echo ' <input class="small-submit" type = "submit" name = "submit" value = "Submit">';
                            }
                            ?>
                        </form>
                        <br>
                        <div class="table">
                            <table class="data display datatable" id="example">
                                <thead>
                                    <tr class="data">
                                        <th class="data">Date</th>
                                        <th class="data">Ref No.</th>
                                        <th class="data">Supplier Name</th>
                                        <th class="data">Amount</th>
                                        <th class="data">Payment Type</th>
                                        <th class="data">Type</th>
                                        <th class="data">Status</th>
                                        <th class="data">Action</th>
                                    </tr>
                                </thead>
                                <?php
                                if (isset($_POST['from'])) {
                                    $date_from = str_replace('/', '-', $_POST['from']);
                                    $date_to = str_replace('/', '-', $_POST['to']) . " 23:59:59";
                                    if ($_POST['status'] == '') {
                                        $sql_data = mysql_query("SELECT * FROM adv WHERE branch_id='$branch_id' and date>='$date_from' and date<='$date_to'");
                                    } else if ($_POST['status'] == 'pending') {
                                        $sql_data = mysql_query("SELECT * FROM adv WHERE branch_id='$branch_id' and date>='$date_from' and date<='$date_to' and status=''");
                                    } else {
                                        $sql_data = mysql_query("SELECT * FROM adv WHERE branch_id='$branch_id' and date>='$date_from' and date<='$date_to' and status='" . $_POST['status'] . "'");
                                    }
                                } else {
                                    $sql_data = mysql_query("SELECT * FROM adv WHERE branch_id='$branch_id' and status='approved'");
                                }

                                while ($rs_data = mysql_fetch_array($sql_data)) {
                                    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_data['supplier_id'] . "'");
                                    $rs_sup = mysql_fetch_array($sql_sup);
                                    $sql_pty = mysql_query("SELECT * FROM adv_paytype WHERE acpty_id='" . $rs_data['acpty_id'] . "'");
                                    $rs_pty = mysql_fetch_array($sql_pty);
                                    $sql_ty = mysql_query("SELECT * FROM adv_type WHERE acty_id='" . $rs_data['acty_id'] . "'");
                                    $rs_ty = mysql_fetch_array($sql_ty);
                                    echo "<tr id='" . $rs_data['ac_id'] . "'>";
                                    echo "<td>" . date("Y/m/d", strtotime($rs_data['date'])) . "</td>";
                                    echo "<td>" . $rs_data['ac_no'] . "</td>";
                                    echo "<td>" . $rs_sup ['supplier_id'] . "_" . $rs_sup ['supplier_name'] . "</td>";
                                    echo "<td>" . $rs_data['amount'] . "</td>";
                                    echo "<td>" . $rs_pty['name'] . "</td>";
                                    echo "<td>" . $rs_ty['name'] . "</td>";
                                    echo "<td>" . strtoupper($rs_data['status']) . "</td>";
                                    echo "<td>";
                                    if ($rs_data['status'] == 'issued') {
                                        echo "<a href='adv_pay_cash.php?ac_id=" . $rs_data['ac_id'] . "'><button>Pay Cash</button></a><br>";
                                        echo "<button onclick='print(" . $rs_data['ac_id'] . ");
                                    '>Print</button><br>";
                                        echo "<a href='adv_view.php?ac_id=" . $rs_data['ac_id'] . "'><button>View</button></a>";
                                    } else {
                                        echo "<button onclick='print(" . $rs_data['ac_id'] . ");
                                    '>Print</button><br>";
                                        echo "<a href='adv_view.php?ac_id=" . $rs_data['ac_id'] . "'><button>View</button></a>";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </table>
                        </div>
                        <br>
                    </main><!-- .content -->
                </div><!-- .container-->

                <aside class="left-sidebar">
                    <iframe src="template/pending2.php" width="367" height="645" scrolling="yes"></iframe>
                </aside><!-- .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template / footer . php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>