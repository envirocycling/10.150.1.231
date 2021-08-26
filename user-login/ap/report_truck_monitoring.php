<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link rel="stylesheet" type="text/css" href="css/pay_table.css" />
        <link href="css/adv_form.css" rel="stylesheet">
        <script>
            $(document).ready(function () {
            $('#supplier_id').select2();
            });</script>
        <style>
            .table{
                font-size: 18px;
            }
            #supplier_id{
                width: 250px;
            }
            #table{
                width: 1000px;
            }
            #header{
                width: 450px;
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
                    <h2>Truck Monitoring</h2>

                    <br>
                    <form action="" method="POST">
                        <table class="table">
                            <tr>
                                <td colspan="2">Supplier Name: <select id="supplier_id" name="supplier_id" >
                                        <option value="">All</option>
                                        <?php
                                        $sql_tr = mysql_query("SELECT supplier_id FROM truck_monitoring GROUP BY supplier_id");
                                        while ($rs_tr = mysql_fetch_array($sql_tr)) {
                                            $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_tr['supplier_id'] . "'");
                                            $rs_sup = mysql_fetch_array($sql_sup);
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
                    <br><br><br>
                    <?php
                    if (isset($_POST['submit'])) {
                        if ($_POST['supplier_id'] == '') {
                            $sql_tr = mysql_query("SELECT * FROM truck_monitoring GROUP BY supplier_id");
                        } else {
                            $sql_tr = mysql_query("SELECT * FROM truck_monitoring WHERE supplier_id='" . $_POST['supplier_id'] . "'");
                        }
                        while ($rs_tr = mysql_fetch_array($sql_tr)) {
                            $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_tr['supplier_id'] . "'");
                            $rs_sup = mysql_fetch_array($sql_sup);

                            echo '<div id="header" class="payTable">';
                            echo '<table>';
                            echo '<tr>';
                            echo '<td colspan="3">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>PLATE NUMBER: </td>';
                            echo '<td>' . $rs_tr['plate_no'] . '</td>';
                            echo '<td></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>PLATE NUMBER: </td>';
                            echo '<td>' . $rs_tr['plate_no'] . '</td>';
                            echo '<td></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>MO. TRUCK RENTAL: </td>';
                            echo '<td>' . number_format($rs_tr['rental'], 2) . '</td>';
                            $rental_bal = $rs_tr['rental'] * $rs_tr['rental_mo'];
                            echo '<td>' . number_format($rental_bal, 2) . '</td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>MO. CASHBOND: </td>';
                            echo '<td>' . number_format($rs_tr['cashbond'], 2) . '</td>';
                            $cashbond_bal = $rs_tr['cashbond'] * $rs_tr['cashbond_mo'];
                            echo '<td>' . number_format($cashbond_bal, 2) . '</td>';
                            echo '</tr>';
                            echo '</table>';
                            echo '</div>';

                            echo '<br><br>';

                            echo '<div id="table" class="payTable">';
                            echo '<table>';
                            echo '<tr>';
                            echo '<td>DATE</td>';
                            echo '<td>REF. NO.</td>';
                            echo '<td>AMORTIZATION <br>(TRUCK RENTAL)</td>';
                            echo '<td>BALANCE</td>';
                            echo '<td>CASHBOND</td>';
                            echo '<td>BALANCE</td>';
                            echo '<td>PENALTY <br>FAILURE <br>TO MET THE <br>QUOTA</td>';
                            echo '</tr>';

                            echo '<tr>';
                            echo '<td>' . date("Y-m-d", strtotime($rs_tr['issuance_date'])) . '</td>';
                            echo '<td>BEG. BAL.</td>';
                            echo '<td></td>';
                            echo '<td>' . number_format($rental_bal, 2) . '</td>';
                            echo '<td></td>';
                            echo '<td>' . number_format($cashbond_bal, 2) . '</td>';
                            echo '<td></td>';
                            echo '</tr>';

                            $total_amort = 0;
                            $total_cashBond = 0;
                            $total_penalty = 0;

                            $sql_trPayment = mysql_query("SELECT * FROM truck_payment WHERE tr_id='" . $rs_tr['tr_id'] . "' and type!='penalty' ORDER BY month,type ASC");
                            while ($rs_trPayment = mysql_fetch_array($sql_trPayment)) {
                                $sql_ref_no = mysql_query("SELECT voucher_no FROM payment WHERE payment_id='" . $rs_trPayment['payment_id'] . "'");
                                $rs_ref_no = mysql_fetch_array($sql_ref_no);
                                echo '<tr>';

                                if ($rs_trPayment['type'] == 'amortization') {
                                    if ($rs_trPayment['status'] == 'paid') {
                                        $rental_bal-=$rs_trPayment['amount'];
                                        echo '<td>' . date("Y-m-d", strtotime($rs_trPayment['date_paid'])) . '</td>';
                                        echo '<td>' . $rs_ref_no['voucher_no'] . '</td>';
                                        echo '<td>' . number_format($rs_trPayment['amount'], 2) . '</td>';
                                        $total_amort += $rs_trPayment['amount'];
                                    } else {
                                        echo '<td>' . date("Y-m-d", strtotime($rs_trPayment['date'])) . '</td>';
                                        echo '<td></td>';
                                        echo '<td></td>';
                                    }
                                    echo '<td>' . number_format($rental_bal, 2) . '</td>';
                                    echo '<td></td>';
                                    echo '<td>' . number_format($cashbond_bal, 2) . '</td>';
                                    echo '<td></td>';
                                }

                                if ($rs_trPayment['type'] == 'cashbond') {

                                    if ($rs_trPayment['status'] == 'paid') {
                                        $cashbond_bal-=$rs_trPayment['amount'];
                                        echo '<td>' . date("Y-m-d", strtotime($rs_trPayment['date_paid'])) . '</td>';
                                        echo '<td>' . $rs_ref_no['voucher_no'] . '</td>';
                                        echo '<td></td>';
                                        echo '<td>' . number_format($rental_bal, 2) . '</td>';
                                        echo '<td>' . number_format($rs_trPayment['amount'], 2) . '</td>';
                                        $total_cashBond+=$rs_trPayment['amount'];
                                    } else {
                                        echo '<td>' . date("Y-m-d", strtotime($rs_trPayment['date'])) . '</td>';
                                        echo '<td></td>';
                                        echo '<td></td>';
                                        echo '<td>' . number_format($rental_bal, 2) . '</td>';
                                        echo '<td></td>';
                                    }

                                    echo '<td>' . number_format($cashbond_bal, 2) . '</td>';
                                    $sql_trPenalty = mysql_query("SELECT * FROM truck_payment WHERE tr_id='" . $rs_tr['tr_id'] . "' and type='penalty' and status='paid' and month='" . $rs_trPayment['month'] . "'");
                                    $rs_trPenalty = mysql_fetch_array($sql_trPenalty);
                                    echo '<td>' . $rs_trPenalty['amount'] . '</td>';
                                    $total_penalty+=$rs_trPenalty['amount'];
                                }

                                echo '</tr>';
                            }

                            $totalPay = 0;
                            $totalCa = 0;
                            $payCa = 0;
                            $sql_ca = mysql_query("SELECT * from adv WHERE supplier_id='" . $rs_tr['supplier_id'] . "' and status='issued' and acty_id='3'") or die(mysql_error());
//                           echo "SELECT * from adv WHERE supplier_id='" . $rs_tr['supplier_id'] . "' and status='issued' and acpty_id='3'";
                            while ($row_ca = mysql_fetch_array($sql_ca)) {
                                $sql_cashPay = mysql_query("SELECT sum(amount) as cashPay from adv_payment WHERE ac_id='" . $row_ca['ac_id'] . "' and can_id='0'") or die(mysql_error());
                                $row_cashPay = mysql_fetch_array($sql_cashPay);

                                $sql_pay = mysql_query("SELECT sum(amount) as pay from payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='" . $row_ca['ac_id'] . "' and payment.status!='cancelled' and payment.status!='deleted'") or die(mysql_error());
                                $row_pay = mysql_fetch_array($sql_pay);

                                $totalCa = $row_ca['amount'];

                                $caBalance = $totalCa - ($row_cashPay['cashPay'] + $row_pay['pay']);
//                                echo $totalCa.' -.'.$row_cashPay['cashPay'].' +'. $row_pay['pay'].'<br>';

                                if($caBalance > 0) {
                                    $totalCasbondBal = $cashbond_bal+$caBalance;
                                    echo '<tr><td>' . date("Y-m-d", strtotime($row_ca['date'])) . '</td>';
                                    echo '<td>'.$row_ca['ac_no'].'</td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                    echo '<td><font color="red">(' . number_format($caBalance, 2) . ')</font></td>';
                                    echo '<td>' . number_format($totalCasbondBal, 2) . '</td>';
                                    echo '<td></td></tr>';
                                    $payCa += $caBalance;
                                    $cashbond_bal = $totalCasbondBal;
                                }
                            }

                            echo "<tr class='total'>";
                            echo '<td><b>TOTAL</b></td>';
                            echo '<td></td>';
                            echo '<td><b>' . number_format($total_amort, 2) . '</b></td>';
                            echo '<td></td>';
                            echo '<td><b>' . number_format(($total_cashBond - $payCa), 2) . '</b></td>';
                            echo '<td></td>';
                            echo '<td><b>' . number_format($total_penalty, 2) . '</b></td>';
                            echo "</tr>";

                            echo '</table>';
                            echo '</div>';
                            echo '<br><br>';
                        }
                    } else {
                        echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
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

