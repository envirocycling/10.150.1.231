<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['gm_id'])) {
    echo "<script>location.replace('../../');</script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Envirocycling Fiber Inc.</title>
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link rel="stylesheet" type="text/css" href="css/pay_table.css" />
        <link href="css/adv_form.css" rel="stylesheet">
        <link href="css/select2.min.css" rel="stylesheet">
        <script type="text/javascript" src="js/select2.min.js"></script>
        <script>
                    $(document).ready(function () {
            $('#supplier_id').select2();
            });</script>
        <style>
            .table{
                font-size: 18px;
            }
            .select2{
                width: 250px;
            }
            #table{
                width: 800px;
            }
            #summary{
                width: 500px;
            }
            .tcal{
                text-transform: uppercase;
                border-radius: 4px;
                height: 30px;
                width: 180px;
                font-size: 18px;
            }
        </style>
        <script type="text/javascript">
                            var tableToExcel = (function () {
                            var uri = 'data:application/vnd.ms-excel;base64,'
                                    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                                    , base64 = function (s) {
                                    return window.btoa(unescape(encodeURIComponent(s)))
                  }
, format = function (s, c) {
return s.replace(/{(\w+)}/g, function (m, p) {
    return c[p];
})
}
return function (table, name) {
if (!table.nodeType)
    table = document.getElementById(table)
var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
window.location.href = uri + base64(format(template, ctx))
}
})()
        </script>
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
                <div style="margin-left: 20px;">
                    <h2>Supplier Advances</h2>

                    <br>
                    <form action="report_supplier_advances.php" method="POST">
                        <table class="table">
                            <tr>
                                <td>From: <input class="tcal" type="text" name="from" value="<?php
                                    if (isset($_POST['from'])) {
                                        echo $_POST['from'];
                                    } else {
                                        echo date("Y/m/d");
                                    }
                                    ?>" size="10" readonly required></td>
                                <td>To: <input class="tcal" type="text" name="to" value="<?php
                                    if (isset($_POST['to'])) {
                                        echo $_POST['to'];
                                    } else {
                                        echo date("Y/m/d");
                                    }
                                    ?>" size="10" readonly required></td>
                            </tr>
                            <tr>
                                <td colspan="2">Supplier Name: <select id="supplier_id" name="supplier_id" required>
                                        <option value=""></option>
                                        <option value="all">All</option>
                                        <?php
                                        $sql_sup = mysql_query("SELECT * FROM supplier");
                                        while ($rs_sup = mysql_fetch_array($sql_sup)) {
                                            echo '<option value="' . $rs_sup['id'] . '">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="submit" class="large-submit" name='submit' value="Submit"></td>
                            </tr>
                        </table>
                    </form>

                    <?php
                    if (isset($_POST['submit'])) {
                        $adv_array = array();
                        $adv_pay_id_array = array();
                        $adv_header_array = array();
                        $adv_idline_array = array();
                        $adv_line_array = array();
                        $adv_dates_array = array();
                        $adv_supplier_array = array();
                        $adv_balance = array();

                        $from = str_replace('/', '-', $_POST['from']);
                        $to = str_replace('/', '-', $_POST['to']) . " 23:59:59";

                        $sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
                        $rs_code = mysql_fetch_array($sql_code);

                        if ($_POST['supplier_id'] == 'all') {
                            $sql_adv = mysql_query("SELECT * FROM adv WHERE (status='issued' or status='paid') and (date_processed>='$from' and date_processed<='$to') and branch_id='7'");
                        } else {
                            $sql_adv = mysql_query("SELECT * FROM adv WHERE supplier_id='" . $_POST['supplier_id'] . "' and (status='issued' or status='paid') and (date_processed>='$from' and date_processed<='$to') and branch_id='7'");
                        }

                        while ($rs_adv = mysql_fetch_array($sql_adv)) {
                            array_push($adv_array, $rs_adv['ac_id']);
                            $adv_pay_id_array[$rs_adv['ac_id']] = $rs_adv['payment_id'];
                            if ($rs_adv['acpty_id'] == '1') {
                                $vn1 = $rs_adv['ac_no'];
                                $adv_header_array[$rs_adv['ac_id']]['date'] = date("Y/m/d", strtotime($rs_adv['date_processed']));
                                $adv_header_array[$rs_adv['ac_id']]['advance_no'] = $vn1;
                                $adv_header_array[$rs_adv['ac_id']]['dr'] = $rs_adv['amount'];
                                $adv_header_array[$rs_adv['ac_id']]['balance'] = $rs_adv['amount'];
                                $adv_balance[$rs_adv['ac_id']] = $rs_adv['amount'];
                            } else {
                                $sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $rs_adv['payment_id'] . "'");
                                $rs_pay = mysql_fetch_array($sql_pay);
                                if ($rs_pay['bank_code'] == 'SBC') {
                                    $vn1 = "SBC_" . $rs_code['code'] . "" . $rs_pay['voucher_no'];
                                } else {
                                    $vn1 = $rs_pay['voucher_no'];
                                }
                                $adv_header_array[$rs_adv['ac_id']]['date'] = $rs_pay['date'];
                                $adv_header_array[$rs_adv['ac_id']]['advance_no'] = $vn1;
                                $adv_header_array[$rs_adv['ac_id']]['dr'] = $rs_adv['amount'];
                                $adv_header_array[$rs_adv['ac_id']]['balance'] = $rs_adv['amount'];
                                $adv_balance[$rs_adv['ac_id']] = $rs_pay['grand_total'];
                            }
                            $sql_adj = mysql_query("SELECT * FROM payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='" . $rs_adv['ac_id'] . "' and payment_adjustment.adj_type='deduct' and payment.status!='cancelled' and payment.status!='deleted'");

//                            $sql_adj = mysql_query("SELECT * FROM payment_adjustment WHERE ac_id = '" . $rs_adv['ac_id'] . "'");
                            while ($rs_adj = mysql_fetch_array($sql_adj)) {
                                $sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id = '" . $rs_adj['payment_id'] . "'");
                                $rs_pay = mysql_fetch_array($sql_pay);
                                if ($rs_pay['bank_code'] == 'SBC') {
                                    $vn = "SBC_" . $rs_code['code'] . "" . $rs_pay['voucher_no'];
                                } else {
                                    $vn = $rs_pay['voucher_no'];
                                }

                                array_push($adv_dates_array, $rs_pay['date']);

                                $adv_line_array[$rs_adv['ac_id']][$rs_pay['date']]['adj']['date'] = $rs_pay['date'];
                                $adv_line_array[$rs_adv['ac_id']][$rs_pay['date']]['adj']['advance_no'] = $vn1;
                                $adv_line_array[$rs_adv['ac_id']][$rs_pay['date']]['adj']['payment_no'] = $vn;
                                $adv_line_array[$rs_adv['ac_id']][$rs_pay['date']]['adj']['cr'] = $rs_adj['amount'];
                                $adv_balance[$rs_adv['ac_id']]-=$rs_adj['amount'];
                            }
                            $sql_adv_pay = mysql_query("SELECT * FROM adv_payment WHERE ac_id = '" . $rs_adv['ac_id'] . "' and status!='cancelled'");
                            while ($rs_adv_pay = mysql_fetch_array($sql_adv_pay)) {
                                array_push($adv_dates_array, $rs_adv_pay['paid_date']);
                                $adv_line_array[$rs_adv['ac_id']][$rs_adv_pay['paid_date']]['cash']['date'] = $rs_adv_pay['paid_date'];
                                $adv_line_array[$rs_adv['ac_id']][$rs_adv_pay['paid_date']]['cash']['advance_no'] = $vn1;
                                $adv_line_array[$rs_adv['ac_id']][$rs_adv_pay['paid_date']]['cash']['payment_no'] = $rs_adv_pay['remarks'];
                                $adv_line_array[$rs_adv['ac_id']][$rs_adv_pay['paid_date']]['cash']['cr'] = $rs_adv_pay['amount'];
                                $adv_balance[$rs_adv['ac_id']]-=$rs_adv_pay['amount'];
                            }

                            $adv_dates = array_unique($adv_dates_array);


                            $sql_sup = mysql_query("SELECT * FROM supplier WHERE id = '" . $rs_adv['supplier_id'] . "'");
                            $rs_sup = mysql_fetch_array($sql_sup);
                            $adv_supplier_array[$rs_adv['ac_id']] = $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'];

                            $sql_type = mysql_query("SELECT * FROM adv_type WHERE acty_id = '" . $rs_adv['acty_id'] . "'");
                            $rs_type = mysql_fetch_array($sql_type);
                            $adv_type_array[$rs_adv['ac_id']] = $rs_type['name'];
                        }
                        echo "<div id = 'excel'>";
                        if ($_POST['supplier_id'] == 'all') {
                            echo '<br>';

                            echo '<h2>Summary</h2>';

                            echo '<div id="summary" class="payTable">';
                            echo '<table>';
                            echo '<tr>';
                            echo '<td>Supplier</td>';
                            echo '<td>Amount</td>';
                            echo '</tr>';
                            foreach ($adv_array as $adv) {
                                echo '<tr>';
                                echo '<td>' . $adv_supplier_array[$adv] . '</td>';
                                echo '<td><b>' . number_format($adv_balance[$adv], 2) . '</b></td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                            echo '</div>';
                        }

                        foreach ($adv_array as $adv) {
                            echo "<br><br>";

                            echo "<h2>" . $adv_supplier_array[$adv] . " (" . $adv_type_array[$adv] . ")</h2>";
                            echo '<div id="table" class="payTable">';
                            echo "<table>";
                            echo "<tr>";
                            echo "<td>Date</td>";
                            echo "<td>Advance No.</td>";
                            echo "<td>Payment No.</td>";
                            echo "<td>DR</td>";
                            echo "<td>CR</td>";
                            echo "<td>Balance</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>" . $adv_header_array[$adv]['date'] . "</td>";
                            echo "<td>" . $adv_header_array[$adv]['advance_no'] . "</td>";
                            echo "<td></td>";
                            echo "<td>" . number_format($adv_header_array[$adv] ['dr'], 2) . "</td>";
                            echo "<td></td>";
                            echo "<td><b>" . number_format($adv_header_array[$adv] ['balance'], 2) . "</b></td>";
                            echo "</tr>";

                            $balance = $adv_header_array[$adv]['balance'];

                            foreach ($adv_dates as $date) {
                                if (!empty($adv_line_array[$adv][$date]['adj']['date'])) {
                                    $balance-=$adv_line_array[$adv][$date]['adj']['cr'];
                                    echo "<tr>";
                                    echo "<td>" . $adv_line_array[$adv][$date]['adj']['date'] . "</td>";
                                    echo "<td>" . $adv_line_array[$adv][$date]['adj']['advance_no'] . "</td>";
                                    echo "<td>" . $adv_line_array[$adv][$date]['adj']['payment_no'] . "</td>";
                                    echo "<td></td>";
                                    echo "<td>" . number_format($adv_line_array[$adv][$date]['adj']['cr'], 2) . "</td>";
                                    echo "<td><b>" . number_format($balance, 2) . "</b></td>";
                                    echo "</tr>";
                                }

                                if (!empty($adv_line_array[$adv][$date]['cash']['date'])) {
                                    $balance-=$adv_line_array[$adv][$date]['cash']['cr'];
                                    echo "<tr>";
                                    echo "<td>" . date("Y/m/d", strtotime($adv_line_array[$adv][$date]['cash']['date'])) . "</td>";
                                    echo "<td>" . $adv_line_array[$adv][$date]['cash']['advance_no'] . "</td>";
                                    echo "<td>" . $adv_line_array[$adv][$date]['cash']['payment_no'] . "</td>";
                                    echo "<td></td>";
                                    echo "<td>" . number_format($adv_line_array[$adv][$date]['cash']['cr'], 2) . "</td>";
                                    echo "<td><b>" . number_format($balance, 2) . "</b></td>";
                                    echo "</tr>";
                                }
                            }
                            echo "</table>";
                            echo "</div>";
                        }
                        echo "</div>";
                        ?>
                        <br>
                        <button onclick="tableToExcel('excel', 'Report')"  class="large-submit">XLS</button>
                        <?php
                        echo "<br><br><br>";
                    } else {
                        echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
                    }
                    ?>
                </div>

            </div><!--.middle-->

            <footer class = "footer">
                <?php include 'template/footer.php';
                ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
