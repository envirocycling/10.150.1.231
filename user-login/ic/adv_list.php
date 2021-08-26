<?php
@session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';
if (!isset($_SESSION['ic_id'])) {
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
            .table {
                border: 1px solid black;
                height: 500px;
                overflow: auto;
                width: 1180px;
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
                <?php
                include 'template/menu.php';
                ?>
                <br>
                <br>
                <div style="margin-left: 10px;"> 
                    <h2>ADVANCES</h2>
                    <br>
                    <form action="adv_list.php" method="POST">
                        <?php
                        if (isset($_POST['from'])) {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" readonly> To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" readonly>
                                        <input class="small-submit" type="submit" name="submit" value="Submit">';
                        } else {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . date('Y/m/d') . '" size="10" readonly> To: <input type="text" class="tcal" name="to" value="' . date('Y/m/d') . '" size="10" readonly>
                                        <input class="small-submit" type="submit" name="submit" value="Submit">';
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
                                    <th class="data">Amount</th>
                                    <th class="data">Type</th>
                                    <th class="data">Justification</th>
                                    <th class="data">Terms</th>
                                    <th class="data">Status</th>
                                    <th class="data">Amount Paid</th>
                                    <th class="data">Remaining Balance</th>
                                    <th class="data">Ageing (days)</th>
                                    <th class="data">Action</th>
                                </tr>
                            </thead>
                            <?php
                            $sql_branch_id = mysql_query("SELECT * FROM company");
                            $rs_branch_id = mysql_fetch_array($sql_branch_id);

                            if (isset($_POST['from'])) {
                                $date_from = str_replace('/', '-', $_POST['from']);
                                $date_to = str_replace('/', '-', $_POST['to']) . " 23:59:59";
                                $sql_data = mysql_query("SELECT * FROM adv WHERE date>='$date_from' and date<='$date_to' and status!='cancelled' and status!='disapproved' and branch_id='" . $rs_branch_id['branch_id'] . "' and acty_id='4' and acpty_id='4'");
                            } else {
                                $sql_data = mysql_query("SELECT * FROM adv WHERE status='' and branch_id='" . $rs_branch_id['branch_id'] . "' and acty_id='4' and acpty_id='4'");
                            }

                            while ($rs_data = mysql_fetch_array($sql_data)) {
                                $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_data['supplier_id'] . "'");
                                $rs_sup = mysql_fetch_array($sql_sup);
                                $sql_pty = mysql_query("SELECT * FROM adv_paytype WHERE acpty_id='" . $rs_data['acpty_id'] . "'");
                                $rs_pty = mysql_fetch_array($sql_pty);
                                $sql_ty = mysql_query("SELECT * FROM adv_type WHERE acty_id='" . $rs_data['acty_id'] . "'");
                                $rs_ty = mysql_fetch_array($sql_ty);
                                if ($rs_data['status'] == '') {
                                    $status = 'PENDING';
                                } else {
                                    $status = strtoupper($rs_data['status']);
                                }

                                if ($status == 'ISSUED') {
                                    $amunt_paid = 0;
                                    $balance = 0;
                                    $sql_cash = mysql_query("SELECT sum(amount) as cash_amount from adv_payment WHERE ac_id='" . $rs_data['ac_id'] . "'");
                                    $row_cash = mysql_fetch_array($sql_cash);

                                    $sql_pay = mysql_query("SELECT sum(amount) as pay_amount from payment_adjustment WHERE ac_id='" . $rs_data['ac_id'] . "'");
                                    $row_pay = mysql_fetch_array($sql_pay);

                                    $amount_paid = $row_cash['cash_amount'] + $row_pay['pay_amount'];
                                    $balance = $rs_data['amount'] - $amount_paid;
                                } else {
                                    $amunt_paid = ' - ';
                                    $balance = ' - ';
                                }
                                
                                $date_issuedData = date('Y/m/d', strtotime($rs_data['date_processed']));
                                $start = new DateTime($date_issuedData);
                                $current_date = date('Y/m/d');
                                $end = new DateTime($current_date);
                                $days2 = $start->diff($end, true)->days;

                                $sundays = intval($days2 / 7) + ($start->format('N') + $days2 % 7 >= 7);

                                $diff = abs(strtotime($current_date) - strtotime($date_issuedData));
                                $years = floor(($diff / (365 * 60 * 60 * 24) / (30 * 60 * 60 * 24)) / (60 * 60 * 24));
                                $months = floor((($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24)) / (60 * 60 * 24));
                                $days = (floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24))) - $sundays;
                                if ($days > 1) {
                                    $aging = $days . ' days';
                                } else {
                                    $aging = $days . ' day';
                                }

                                echo "<tr id='" . $rs_data['ac_id'] . "'>";
                                echo "<td>" . date("Y/m/d", strtotime($rs_data['date'])) . "</td>";
                                echo "<td>" . $rs_sup ['supplier_id'] . "_" . $rs_sup ['supplier_name'] . "</td>";
                                echo "<td>" . $rs_data['amount'] . "</td>";
                                echo "<td>" . $rs_pty['name'] . "</td>";
                                echo "<td>" . mysql_real_escape_string(strtoupper($rs_data['justification'])) . "</td>";
                                echo "<td>" . mysql_real_escape_string(strtoupper($rs_data['terms'])) . "</td>";
                                echo "<td>" . $status . "</td>";
                                echo "<td>" . number_format($amunt_paid, 2) . "</td>";
                                echo "<td>" . number_format($balance, 2) . "</td>";
                                echo "<td>" . $aging . "</td>";
                                echo "<td><a href='adv_view.php?ac_id=" . $rs_data['ac_id'] . "'><button>View</button></a></td>";
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