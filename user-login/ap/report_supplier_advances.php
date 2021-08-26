<?php
@session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
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
                        $adv_array[] = array();
                        $adv_id_array = array();
                        $adv_supplier_array = array();
                        $adv_supplier_id_array = array();
                        $adv_supplier_name_array = array();
                        $adv_supplier_balance_array = array();
//                        $adv_supplier_array = array();
//                        $adv_balance = array();

                        $from = str_replace('/', '-', $_POST['from']);
                        $to = str_replace('/', '-', $_POST['to']) . " 23:59:59";

                        $sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
                        $rs_code = mysql_fetch_array($sql_code);

                        if ($_POST['supplier_id'] == 'all') {
                            $sql_adv = mysql_query("SELECT * FROM adv WHERE (status='issued' or status='paid') and (date_processed>='$from' and date_processed<='$to')");
                        } else {
                            $sql_adv = mysql_query("SELECT * FROM adv WHERE supplier_id='" . $_POST['supplier_id'] . "' and (status='issued' or status='paid') and (date_processed>='$from' and date_processed<='$to')");
                        }

                        $c = 0;
                        while ($rs_adv = mysql_fetch_array($sql_adv)) {
                            array_push($adv_id_array, $rs_adv['ac_id']);
                            if ($rs_adv['acpty_id'] == '3') {
                                if ($rs_adv['payment_id'] == 0) {
                                    $vn1 = "SBC_PAM" . $rs_adv['voucher_no'];
                                    $ac_date = date("Y/m/d", strtotime($rs_adv['date_processed']));
                                } else {
                                    $sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $rs_adv['payment_id'] . "'");
                                    $rs_pay = mysql_fetch_array($sql_pay);

                                    $vn1 = "SBC_" . $rs_code['code'] . "" . $rs_pay['voucher_no'];
                                    $ac_date = $rs_pay['date'];
                                }
                            } else if ($rs_adv['acpty_id'] == '2') {
                                if ($rs_adv['payment_id'] == 0) {
                                    $vn1 = $rs_adv['voucher_no'];
                                    -
                                            $ac_date = date("Y/m/d", strtotime($rs_adv['date_processed']));
                                } else {
                                    $sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $rs_adv['payment_id'] . "'");
                                    $rs_pay = mysql_fetch_array($sql_pay);

                                    $vn1 = $rs_pay['voucher_no'];
                                    $ac_date = $rs_pay['date'];
                                }
                            } else {
                                $vn1 = $rs_adv['ac_no'];
                                $ac_date = date("Y/m/d", strtotime($rs_adv['date_processed']));
                            }

                            $adv_array[$rs_adv['ac_id']][] = array(
                                'date' => $ac_date,
                                'advance_no' => $vn1,
                                'payment_no' => $rs_adv['amount'],
                                'balance' => $rs_adv['amount'],
                            );
                            $adv_balance[$rs_adv['ac_id']] = $rs_adv['amount'];

                            $sql_adj = mysql_query("SELECT * FROM payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='" . $rs_adv['ac_id'] . "' and payment_adjustment.adj_type='deduct' and payment.status!='cancelled' and payment.status!='deleted' ORDER BY date ASC");

                            while ($rs_adj = mysql_fetch_array($sql_adj)) {
                                $sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id = '" . $rs_adj['payment_id'] . "'");
                                $rs_pay = mysql_fetch_array($sql_pay);
                                if ($rs_pay['bank_code'] == 'SBC') {
                                    $vn = "SBC_" . $rs_code['code'] . "" . $rs_pay['voucher_no'];
                                } else {
                                    $vn = $rs_pay['voucher_no'];
                                }

                                $adv_array[$rs_adv['ac_id']][] = array(
                                    'date' => $rs_pay['date'],
                                    'advance_no' => $vn1,
                                    'payment_no' => $vn,
                                    'balance' => $rs_adj['amount'],
                                );

                                $adv_balance[$rs_adv['ac_id']]-=$rs_adj['amount'];
                            }
                            $sql_adv_pay = mysql_query("SELECT * FROM adv_payment WHERE ac_id = '" . $rs_adv['ac_id'] . "' and status!='cancelled'");
                            while ($rs_adv_pay = mysql_fetch_array($sql_adv_pay)) {
                                $adv_array[$rs_adv['ac_id']][] = array(
                                    'date' => $rs_adv_pay['paid_date'],
                                    'advance_no' => $vn1,
                                    'payment_no' => $rs_adv_pay['remarks'],
                                    'balance' => $rs_adv_pay['amount'],
                                );

                                $adv_balance[$rs_adv['ac_id']]-=$rs_adv_pay['amount'];
                            }

                            array_push($adv_supplier_id_array, $rs_adv['supplier_id']);

                            if (!isset($adv_supplier_balance_array[$rs_adv['supplier_id']])) {
                                $adv_supplier_balance_array[$rs_adv['supplier_id']] = 0;
                            }

                            $adv_supplier_balance_array[$rs_adv['supplier_id']]+=$adv_balance[$rs_adv['ac_id']];

                            $sql_sup = mysql_query("SELECT * FROM supplier WHERE id = '" . $rs_adv['supplier_id'] . "'");
                            $rs_sup = mysql_fetch_array($sql_sup);

                            if (!isset($adv_supplier_name_array[$rs_adv['supplier_id']])) {
                                $adv_supplier_name_array[$rs_adv['supplier_id']] = $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'];
                            }

                            $adv_supplier_array[$rs_adv['ac_id']] = $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'];

                            $sql_type = mysql_query("SELECT * FROM adv_type WHERE acty_id = '" . $rs_adv['acty_id'] . "'");
                            $rs_type = mysql_fetch_array($sql_type);
                            $adv_type_array[$rs_adv['ac_id']] = $rs_type['name'];
                            $c++;
                        }

                        $adv_supplier_id_array_unq = array_unique($adv_supplier_id_array);
                        echo "<div id = 'excel'>";
                        echo '<br>';

                        echo '<h2>Summary</h2>';

                        echo '<div id="summary" class="payTable">';
                        echo '<table>';
                        echo '<tr>';
                        echo '<td>Supplier</td>';
                        echo '<td>Amount</td>';
                        echo '</tr>';
                        foreach ($adv_supplier_id_array_unq as $supplier_id) {
                            echo '<tr>';
                            echo '<td>' . $adv_supplier_name_array[$supplier_id] . '</td>';
                            echo '<td><b>' . number_format($adv_supplier_balance_array[$supplier_id], 2) . '</b></td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '</div>';


                        echo "<br>";
                        $c2 = 0;
                        foreach ($adv_id_array as $adv) {
                            echo "<br><br>";
                            $c2++;
                            echo "<h2>" . $adv_supplier_array[$adv] . " (" . $adv_type_array[$adv] . ")</h2>";
                            echo '<div id="table" class="payTable">';
                            echo "<table>";
                            echo "<tr>";
                            echo "<td>$c2 Date</td>";
                            echo "<td>Advance No.</td>";
                            echo "<td>Payment No.</td>";
                            echo "<td>DR</td>";
                            echo "<td>CR</td>";
                            echo "<td>Balance</td>";
                            echo "</tr>";

                            $balance = 0;

                            $adv_data = $adv_array[$adv];
                            foreach ($adv_data as $new_data) {
                                if ($balance == 0) {
                                    $balance = $new_data['balance'];
                                } else {
                                    $balance-=$new_data['balance'];
                                }
                                echo "<tr>";
                                echo "<td>" . $new_data['date'] . "</td>";
                                echo "<td>" . $new_data['advance_no'] . "</td>";
                                echo "<td>" . $new_data['payment_no'] . "</td>";
                                echo "<td></td>";
                                echo "<td>" . number_format($new_data['balance'], 2) . "</td>";
                                echo "<td><b>" . number_format($balance, 2) . "</b></td>";
                                echo "</tr>";
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
