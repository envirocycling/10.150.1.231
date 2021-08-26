<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['bh_id'])) {
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
        <style>
            table{
                font-size: 15px;
            }
            td{
                padding-left: 7px;
                padding-right: 7px;
            }
            .td_bold{
                font-weight: bold;
            }
            .blue{
                font-weight: bold;
                background-color: #8ea9db;
            }
            .yellow{
                font-weight: bold;
                background-color: #ffff00;
            }
            .orange{
                font-weight: bold;
                background-color: #ffc000;
            }
            .peach{
                background-color: #fce4d6;
            }
            .grey{
                font-weight: bold;
                background-color: #dbdbdb
            }
            .grey2{
                background-color: #dbdbdb
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
            <div class="middle" align="center">
                <?php
                include 'template/menu.php';
                ?>
                <br>
                <h2>EFI Delivery Performance</h2>
                (TIPCO, MULTIPLY & FSI)
                <br>
                <br>
                <form action="index.php" method="POST">
                    From: <input class="tcal" type="text" name="from" value="<?php echo date("Y/m/d"); ?>" size="10" required> To: <input class="tcal" type="text" name="to" value="<?php echo date("Y/m/d"); ?>" size="10" required> <input type="submit" name="submit" value="Submit">
                </form>
                <br>
                <?php
                if (isset($_POST['submit'])) {
                    $from = $_POST['from'];
                    $to = $_POST['to'];
                    $month = date('Y/m', strtotime($to));
                } else {
                    $from = date('Y/m') . "/01";
                    $to = date("Y/m/d");
                    $month = date("Y/m");
                }
                $target_array = array();
                $total_target_array = array();
                $actual_array = array();
                $total_actual_array = array();

                $td = 0;
                $sql_mat = mysql_query("SELECT * FROM material WHERE class='1'");
                while ($rs_mat = mysql_fetch_array($sql_mat)) {
                    $td++;
                }
                $td+=3;

                $sql_branch = mysql_query("SELECT * FROM branches");
                while ($rs_branch = mysql_fetch_array($sql_branch)) {
                    $sql_mat = mysql_query("SELECT * FROM material WHERE class='1'");
                    while ($rs_mat = mysql_fetch_array($sql_mat)) {
                        $sql_target = mysql_query("SELECT sum(target) FROM monthly_target WHERE branch_id='" . $rs_branch['branch_id'] . "' and material_id='" . $rs_mat['material_id'] . "' and month='$month'");
                        $rs_target = mysql_fetch_array($sql_target);
                        $target_array[$rs_branch['branch_id']][$rs_mat['material_id']] = $rs_target['sum(target)'];
                        if (!isset($total_target_array[$rs_mat['material_id']])) {
                            $total_target_array[$rs_mat['material_id']] = 0;
                        }
                        $total_target_array[$rs_mat['material_id']]+=$rs_target['sum(target)'];
                    }

                    $sql_mat = mysql_query("SELECT * FROM material WHERE class='1'");
                    while ($rs_mat = mysql_fetch_array($sql_mat)) {

                        $to_add_q = '';
                        $sql_mat2 = mysql_query("SELECT * FROM material WHERE class='2' and under_by='" . $rs_mat['material_id'] . "'");
                        while ($rs_mat2 = mysql_fetch_array($sql_mat2)) {
                            $to_add_q .= " or scale_outgoing_details.material_id = '" . $rs_mat2['material_id'] . "'";
                        }

//                        echo "SELECT sum(scale_outgoing_details.corrected_weight) FROM scale_outgoing INNER JOIN scale_outgoing_details ON scale_outgoing.trans_id=scale_outgoing_details.trans_id WHERE scale_outgoing.branch_id='" . $rs_branch['branch_id'] . "' and (scale_outgoing_details.material_id='" . $rs_mat['material_id'] . "' $to_add_q) and (scale_outgoing.date>='$from' and scale_outgoing.date<='$to') <br><br>";

                        $sql_actual = mysql_query("SELECT sum(scale_outgoing_details.corrected_weight) FROM scale_outgoing INNER JOIN scale_outgoing_details ON scale_outgoing.trans_id=scale_outgoing_details.trans_id WHERE scale_outgoing.checked='1' and scale_outgoing.branch_id='" . $rs_branch['branch_id'] . "' and (scale_outgoing_details.material_id='" . $rs_mat['material_id'] . "' $to_add_q) and (scale_outgoing.date>='$from' and scale_outgoing.date<='$to')");
                        $rs_actual = mysql_fetch_array($sql_actual);
                        $actual_array[$rs_branch['branch_id']][$rs_mat['material_id']] = $rs_actual['sum(scale_outgoing_details.corrected_weight)'];
                        if (!isset($total_actual_array[$rs_mat['material_id']])) {
                            $total_actual_array[$rs_mat['material_id']] = 0;
                        }
                        $total_actual_array[$rs_mat['material_id']]+=$rs_actual['sum(scale_outgoing_details.corrected_weight)'];
                    }
                }

                echo "<table border='1'>";
                echo "<tr class='blue'>";
                if (isset($_POST['submit'])) {
                    echo "<td colspan='$td' align='center'>EFI Overall as from " . date("F d", strtotime($_POST['from'])) . " to " . date("d, Y", strtotime($_POST['to'])) . "</td>";
                } else {
                    echo "<td colspan='$td' align='center'>EFI Overall as of " . date("F d, Y") . "</td>";
                }

                echo "</tr>";
                echo "<tr class='grey'>";
                echo "<td></td>";
                $sql_mat = mysql_query("SELECT * FROM material WHERE class='1'");
                while ($rs_mat = mysql_fetch_array($sql_mat)) {
                        echo "<td>" . $rs_mat['code'] . "</td>";
                   
                }
                echo "<td class='yellow'>TOTAL</td>";
                echo "<td class='orange'></td>";
                echo "</tr>";
                echo "<tr class='grey2'>";
                echo "<td class='td_bold'>TARGET</td>";
                $overall_total_target = 0;
                $sql_mat = mysql_query("SELECT * FROM material WHERE class='1'");
                while ($rs_mat = mysql_fetch_array($sql_mat)) {
                    $sql_target = mysql_query("SELECT * FROM monthly_target WHERE branch_id='" . $rs_branch['branch_id'] . "' and material_id='" . $rs_mat['material_id'] . "' and month='$month'");
                    $rs_target = mysql_fetch_array($sql_target);
                        echo "<td>" . $total_target_array[$rs_mat['material_id']] . "</td>";
                    
                    $overall_total_target+=$total_target_array[$rs_mat['material_id']];
                }
                echo "<td class='yellow'>$overall_total_target</td>";
                echo "<td class='orange'></td>";
                echo "</tr>";
                echo "<tr class='grey2'>";
                echo "<td class='td_bold'>ACTUAL</td>";
                $overall_total_actual = 0;
                $sql_mat = mysql_query("SELECT * FROM material WHERE class='1'");
                while ($rs_mat = mysql_fetch_array($sql_mat)) {
                    $sql_actual = mysql_query("SELECT sum(scale_outgoing_details.corrected_weight) FROM scale_outgoing INNER JOIN scale_outgoing_details ON scale_outgoing.trans_id=scale_outgoing_details.trans_id WHERE scale_outgoing.branch_id='" . $rs_branch['branch_id'] . "' and scale_outgoing_details.material_id='" . $rs_mat['material_id'] . "' and scale_outgoing.date like '%$month%'");
                    $rs_actual = mysql_fetch_array($sql_actual);
                    echo "<td>" . round(($total_actual_array[$rs_mat['material_id']] / 1000), 2) . "</td>";
                    $overall_total_actual+=$total_actual_array[$rs_mat['material_id']];
                }
                echo "<td class='yellow'>" . round($overall_total_actual / 1000, 2) . "</td>";
                echo "<td class='orange'>" . round((($overall_total_actual / 1000) / $overall_total_target) * 100) . " %</td>";
                echo "</tr>";

                $sql_branch = mysql_query("SELECT * FROM branches");
                while ($rs_branch = mysql_fetch_array($sql_branch)) {
                    echo "<tr class='blue'>";
                    echo "<td colspan='$td' align='center'>" . $rs_branch['branch_name'] . " MTD</td>";
                    echo "</tr>";
                    echo "</tr>";

                    echo "<tr class='grey2'>";
                    echo "<td class='td_bold'>TARGET</td>";
                    $total_target = 0;
                    $sql_mat = mysql_query("SELECT * FROM material WHERE class='1'");
                    while ($rs_mat = mysql_fetch_array($sql_mat)) {
                        $sql_target = mysql_query("SELECT * FROM monthly_target WHERE branch_id='" . $rs_branch['branch_id'] . "' and material_id='" . $rs_mat['material_id'] . "' and month='$month'");
                        $rs_target = mysql_fetch_array($sql_target);
                        echo "<td>" . $target_array[$rs_branch['branch_id']][$rs_mat['material_id']] . "</td>";
                        $total_target+=$target_array[$rs_branch['branch_id']][$rs_mat['material_id']];
                    }
                    echo "<td class='yellow'>$total_target</td>";
                    echo "<td class='orange'></td>";
                    echo "</tr>";
                    echo "<tr class='grey2'>";
                    echo "<td class='td_bold'>ACTUAL</td>";
                    $total_actual = 0;
                    $sql_mat = mysql_query("SELECT * FROM material WHERE class='1'");
                    while ($rs_mat = mysql_fetch_array($sql_mat)) {
                        $sql_actual = mysql_query("SELECT sum(scale_outgoing_details.corrected_weight) FROM scale_outgoing INNER JOIN scale_outgoing_details ON scale_outgoing.trans_id=scale_outgoing_details.trans_id WHERE scale_outgoing.branch_id='" . $rs_branch['branch_id'] . "' and scale_outgoing_details.material_id='" . $rs_mat['material_id'] . "' and scale_outgoing.date like '%$month%'");
                        $rs_actual = mysql_fetch_array($sql_actual);
                        echo "<td>" . round($actual_array[$rs_branch['branch_id']][$rs_mat['material_id']] / 1000, 2) . "</td>";
                        $total_actual+=$actual_array[$rs_branch['branch_id']][$rs_mat['material_id']];
                    }
                    echo "<td class='yellow'>" . round($total_actual / 1000, 2) . "</td>";
                    echo "<td class = 'orange'>" . round((($total_actual / 1000) / $total_target) * 100) . " %</td>";
                    echo "</tr>";
                }
                echo "</table>";

//                $sql = mysql_query("SELECT * FROM scale_receiving");
//                while ($rs = mysql_fetch_array($sql)) {
//                    echo $rs['supplier_id'] . "<br>";
//                }
                ?>
                <br>
                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>