<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/pay_table.css" />
        <link href="css/adv_form.css" rel="stylesheet">
        <style>
            body{
                text-align: center;
                font: 18px Arial, sans-serif;
            }
            .table{
                font-size: 18px;
            }
            #supplier_id{
                width: 250px;
            }
            #table{
                width: 1000px;
            }
            .tcal{
                text-transform: uppercase;
                border-radius: 4px;
                height: 30px;
                width: 180px;
                font-size: 18px;
            }
            .val{
                font-weight: bold;
                border-bottom: 1px solid black;
            }
        </style>
        <script type="text/javascript">             var tableToExcel = (function () {
                    var uri = 'data:application/vnd.ms-excel;base64,'
                    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                                ,base64 = function (s) {
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
    <center>
        <?php
        if (isset($_GET['vms_id'])) {
            ?>
            <h2>TRUCK RETURN AND CLEARANCE FORM</h2>

            <br>

            <?php
            $sql_tr = mysql_query("SELECT * FROM truck_monitoring WHERE vms_id='" . $_GET['vms_id'] . "'");
            while ($rs_tr = mysql_fetch_array($sql_tr)) {
                $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_tr['supplier_id'] . "'");
                $rs_sup = mysql_fetch_array($sql_sup);

                echo "<table width='900'>";
                echo "<tr>";
                echo "<td width='100'>Date: </td>";
                echo "<td width='300' class='val'></td>";
                echo "<td></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>To: </td>";
                echo "<td width='300' class='val'></td>";
                echo "<td></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>From: </td>";
                echo "<td width='300' class='val'></td>";
                echo "<td></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>Subject: </td>";
                echo "<td width='300' class='val'></td>";
                echo "<td></td>";
                echo "</tr>";
                echo "</table>";
                echo "<br><br><br>";
                echo '<table width="500">';
                echo '<tr>';
                echo '<td width="200">Start of Contract: </td>';
                echo '<td class="val">' . date("M d, Y", strtotime($rs_tr['issuance_date'])) . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td>Date of Termination: </td>';
                echo '<td class="val"></td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td>Monthly Amortization: </td>';
                echo '<td class="val">' . number_format($rs_tr['rental'], 2) . '</td>';
                $rental_bal = $rs_tr['rental'] * $rs_tr['rental_mo'];
                echo '</tr>';
                echo '<tr>';
                echo '<td>Monthly Cashbond: </td>';
                echo '<td class="val">' . number_format($rs_tr['cashbond'], 2) . '</td>';
                $cashbond_bal = $rs_tr['cashbond'] * $rs_tr['cashbond_mo'];
                echo '</tr>';
                echo '<tr>';
                echo '<td>Is Cashbond returnable: </td>';
                echo '<td class="val"></td>';
                echo '</tr>';
                echo '</table>';

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
                echo '<td>CURRENT <br>VOLUME</td>';
                echo '<td>QUOTA</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td>' . date("Y-m-d", strtotime($rs_tr['issuance_date'])) . '</td>';
                echo '<td>BEG. BAL.</td>';
                echo '<td></td>';
                echo '<td>' . number_format($rental_bal, 2) . '</td>';
                echo '<td></td>';
                echo '<td>' . number_format($cashbond_bal, 2) . '</td>';
                echo '<td></td>';
                echo '<td></td>';
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
                            $total_amort+= $rs_trPayment['amount'];
                        } else {
                            echo '<td>' . date("Y-m-d", strtotime($rs_trPayment['date'])) . '</td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        echo '<td>' . number_format($rental_bal, 2) . '</td>';
                        echo '<td></td>';
                        echo '<td>' . number_format($cashbond_bal, 2) . '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
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
                        $sql_trPenalty = mysql_query("SELECT * FROM truck_payment WHERE tr_id='" . $rs_tr['tr_id'] . "' and type='penalty' and month='" . $rs_trPayment['month'] . "'");
                        $rs_trPenalty = mysql_fetch_array($sql_trPenalty);
                        echo '<td>' . $rs_tr['penalty'] . '</td>';
                        $total_penalty+=$rs_tr['penalty'];

                        $sql_sup_del = mysql_query("SELECT sum(scale_receiving_details.corrected_weight) as corrected_weight FROM scale_receiving INNER JOIN scale_receiving_details ON scale_receiving.trans_id=scale_receiving_details.trans_id WHERE scale_receiving.supplier_id='" . $rs_tr['supplier_id'] . "' and scale_receiving.date like '%" . date("Y/m", strtotime($rs_trPayment['date'])) . "%'");
                        $rs_sup_del = mysql_fetch_array($sql_sup_del);
                        if ($rs_sup_del['corrected_weight'] < $rs_tr['proposed_volume']) {

                            $sql_check = mysqli_query($conn, "SELECT * FROM `truck_penalty_reqremove` WHERE tr_id='" . $rs_tr['tr_id'] . "' and month='" . $rs_trPayment['month'] . "' and status!='disapproved'");
                            $rs_check = mysqli_fetch_array($sql_check);
                            $rs_count = mysqli_num_rows($sql_check);

                            echo '<td style="color: red; font-weight: bold;">' . round($rs_sup_del['corrected_weight'], 2) . '</td>';
                            echo '<td>' . $rs_tr['proposed_volume'] . '</td>';
                        } else {
                            echo '<td>' . round($rs_sup_del['corrected_weight'], 2) . '</td>';
                            echo '<td>' . $rs_tr['proposed_volume'] . '</td>';
                        }
                    }

                    echo '</tr>';
                }

                $total_amort = 0;
                $total_cashBond = 0;
                $total_penalty = 0;

                echo '<tr class="total">';
                echo '<td><b>TOTAL</b></td>';
                echo '<td></td>';
                echo '<td><b>' . number_format($total_amort, 2) . '</b></td>';
                echo '<td></td>';
                echo '<td><b>' . number_format($total_cashBond, 2) . '</b></td>';
                echo '<td></td>';
                echo '<td><b>' . number_format($total_penalty, 2) . '</b></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '</tr>';

                echo '</table>';
                echo '</div>';
                echo '<br><br><br>';
            }
        }
        ?>

        <table width="1000" border="0">
            <tr>
                <td width="150">Prepared by: </td>
                <td></td>
                <td width="100"></td>
                <td width="150">Verified by: </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="val"></td>
                <td></td>
                <td></td>
                <td class="val"></td>
            </tr>
            <tr>
                <td></td>
                <td>Branch AP</td>
                <td></td>
                <td></td>
                <td>Branch Head</td>
            </tr>
        </table>
        <br><br>
        <table width="1000" border="0">
            <tr>
                <td width="150">Noted by: </td>
                <td></td>
                <td width="100"></td>
                <td width="150">Acknowledged by: </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="val"></td>
                <td></td>
                <td></td>
                <td class="val"></td>
            </tr>
            <tr>
                <td></td>
                <td>General Manager</td>
                <td></td>
                <td></td>
                <td>Pampanga Accounting</td>
            </tr>
        </table>
    </center>
</body>
</html>

