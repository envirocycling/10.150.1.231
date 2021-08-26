<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
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
        <style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
    .submitq {
        height: 20px;
        width: 60px;
    }
    .total {
        background-color: yellow;
        font-weight: bold;
    }
</style>
<link href="../src/facebox_2.css" media="screen" rel="stylesheet" type="text/css" />
<script src="../src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('a[rel*=facebox]').facebox({
            loadingImage: '../src/loading.gif',
            closeImage: '../src/closelabel.png'
        })
    })
</script>
        <style>
            #example{
                border-width:50%;
                font-size: 13px;
            }
            .total {
                font-weight: bold;
                background-color: yellow;
            }
        </style>
        <style>
            .tabless {
                border: 1px solid black;
                height: 500px;
                overflow: auto;
                width: 1160px;
            }
            .submit{
                height: 30px;
                width: 100px;
                font-size: 15px;
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
                <div style="margin-left: 10px;">
                    <br>
                    <h2>PAID PAPER BUYING</h2>
                    <br>
                    <form action="paid_paper_buying.php" method="POST">
                        <?php
                        if (isset($_POST['from'])) {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" required>';
                            echo ' Type: <select name="payment">';
                            if ($_POST['payment'] != '') {
                                echo '<option value="' . $_POST['payment'] . '">' . $_POST['payment'] . '</option>';
                            }
                            echo '<option value="">All</option>';
                            echo '<option value="Cheque">Cheque</option>';
                            echo '<option value="Digibanker">Digibanker</option>';
                            echo '</select> ';
                            echo '<input class="submit" type="submit" name="submit" value="Submit">';
                        } else {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . date('Y/m/d') . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . date('Y/m/d') . '" size="10" required>';
                            echo ' Type: <select name="payment">';
                            echo '<option value="">All</option>';
                            echo '<option value="Cheque">Cheque</option>';
                            echo '<option value="Digibanker">Digibanker</option>';
                            echo '</select> ';
                            echo '<input class="submit" type="submit" name="submit" value="Submit">';
                        }
                        ?>
                    </form>
                    <br>
                    <?php
                    if (isset($_POST['from'])) {
                        $total_weight = 0;
                        $total_amount = 0;
                        $total_tot_ts_fee = 0;
                        $total_tot_adjustment = 0;
                        $total_net_amount = 0;
                        $de_tot_amount = 0;
                        echo "<div class='tabless'>";
                        echo '<table class="data display datatable" id="example">
                            <thead>
                            <tr class="data">
                            <th class="data">Date</th>
                            <th class="data">Priority #</th>
							<th class="data">Cheque No.</th>
							<th class="data">Payee</th>
                            <th class="data">Supplier Name</th>
                            <th class="data">Plate #</th>
                            <th class="data">WP Grade</th>
                            <th class="data">Weight</th>
                            <th class="data">Unit Cost</th>
                            <th class="data">Paper Buying</th>
                            <th class="data">Adjustments</th>
                            <th class="data">Net Paper Buying</th>
                            </tr>
                            </thead>';

                        if ($_POST['payment'] == 'Cheque') {
                            $sql_paid = mysql_query("SELECT * FROM scale_receiving INNER JOIN payment ON scale_receiving.payment_id=payment.payment_id WHERE payment.date>='" . $_POST['from'] . "' and payment.date<='" . $_POST['to'] . "' and scale_receiving.status='paid' and payment.bank_code!='SBC'");
                        } else if ($_POST['payment'] == 'Digibanker') {
                            $sql_paid = mysql_query("SELECT * FROM scale_receiving INNER JOIN payment ON scale_receiving.payment_id=payment.payment_id WHERE payment.date>='" . $_POST['from'] . "' and payment.date<='" . $_POST['to'] . "' and scale_receiving.status='paid' and payment.bank_code='SBC'");
                        } else {
                            $sql_paid = mysql_query("SELECT * FROM scale_receiving INNER JOIN payment ON scale_receiving.payment_id=payment.payment_id WHERE payment.date>='" . $_POST['from'] . "' and payment.date<='" . $_POST['to'] . "' and scale_receiving.status='paid'");
                        }
//                        $sql_paid = mysql_query("SELECT * FROM scale_receiving WHERE date_paid>='" . $_POST['from'] . "' and date_paid<='" . $_POST['to'] . "' and status='paid'");
                        while ($rs_paid = mysql_fetch_array($sql_paid)) {

                            $sql_deduction = mysql_query("SELECT sum(amount) FROM payment_adjustment WHERE payment_id='" . $rs_paid['payment_id'] . "' and adj_type='deduct'");
                            $rs_deduction = mysql_fetch_array($sql_deduction);

                            $deduction = $rs_deduction['sum(amount)'];

                            $sql_total_conso = mysql_query("SELECT sum(scale_receiving_details.corrected_weight) FROM scale_receiving_details INNER JOIN scale_receiving ON scale_receiving_details.trans_id=scale_receiving.trans_id WHERE scale_receiving.payment_id='" . $rs_paid['payment_id'] . "'");
                            $rs_total_conso = mysql_fetch_array($sql_total_conso);

                            $de_tot_amount = $rs_total_conso['sum(scale_receiving_details.corrected_weight)'];

                            
                            $sql_total = mysql_query("SELECT sum(corrected_weight), count(trans_id) FROM scale_receiving_details WHERE trans_id = '" . $rs_paid['trans_id'] . "'");
                            $rs_total = mysql_fetch_array($sql_total);
                            $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id = '" . $rs_paid['trans_id'] . "'");
                            $ts_fee = $rs_ts_fee['price'] * $rs_total['count(trans_id)'];
                            while ($rs_details = mysql_fetch_array($sql_details)) {
                                $amount = 0;
                                echo "<tr>";
                                echo "<td>" . $rs_paid['date_paid'] . "</td>";
                                echo "<td>" . $rs_paid['priority_no'] . "</td>";
								echo "<td>";
				echo  $rs_paid['cheque_no'] ;
				if(strtoupper($rs_paid['bank_code']) == 'SBC'){
				echo  $rs_paid['voucher_no'] ;
				}
				echo "</td>";
								echo "<td>" . $rs_paid['cheque_name'] . "</td>";
                                $sql_sup = mysql_query("SELECT * FROM supplier WHERE id = '" . $rs_paid['supplier_id'] . "'");
                                $rs_sup = mysql_fetch_array($sql_sup);
                                echo "<td>" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</td>";
                                echo "<td>" . $rs_paid['plate_number'] . "</td>";
                                $sql_mat = mysql_query("SELECT * FROM material WHERE material_id = '" . $rs_details['material_id'] . "'");
                                $rs_mat = mysql_fetch_array($sql_mat);
                                echo "<td>" . $rs_mat['code'] . "</td>";
                                echo "<td>" . $rs_details['corrected_weight'] . "</td>";
                                $total_weight+=$rs_details['corrected_weight'];
                                if ($rs_details['adj_price'] != '') {
                                    echo "<td>" . number_format($rs_details['price'] + $rs_details['adj_price'], 2) . "</td>";
                                } else {
                                    echo "<td>" . $rs_details['price'] . "</td>";
                                }
                                if ($rs_details['total_amount'] != '') {
                                    $amount = $rs_details['total_amount'];
                                    echo "<td>" . number_format($rs_details['total_amount'], 2) . "</td>";
                                } else {
                                    $amount = $rs_details['amount'];
                                    echo "<td>" . number_format($rs_details['amount'], 2) . "</td>";
                                }
                                $mul = $ts_fee * $rs_details['corrected_weight'];
                                $mul2 = $deduction * $rs_details['corrected_weight'];
                               

                                if ($de_tot_amount == '0') {
                                    $total_deduction = 0;
                                } else {
                                    $total_deduction = $mul2 / $de_tot_amount;
                                }


                                echo "<td>" . number_format($total_deduction, 2) . "</td>";
                                $total_tot_ts_fee+=$total_ts_fee;
                                $total_amount+=$amount;
                                $total_tot_adjustment+=$total_deduction;
                                echo "<td>" . number_format($amount - ($total_ts_fee + $total_deduction), 2) . "</td>";
                                $total_net_amount+=$amount - ($total_ts_fee + $total_deduction);
                                echo "</tr>";
                            }
                        }
                        echo "<tr class = 'total'>";
                        echo "<td>!TOTAL!</td>";
                        echo "<td></td>";
                        echo "<td></td>";
						echo "<td></td>";
						echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td>$total_weight</td>";
                        echo "<td></td>";
                        echo "<td>" . number_format($total_amount, 2) . "</td>";
                        echo "<td>" . number_format($total_tot_ts_fee, 2) . "</td>";
                        echo "<td>" . number_format($total_tot_adjustment, 2) . "</td>";
                        echo "<td>" . number_format($total_net_amount, 2) . "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                        echo "<br>";
                        ?>
                        <input class="submit" type="button" onclick="tableToExcel('example', 'Paper Buying')" value="Export XLS">
                        <?php
                        echo "<br><br>";
                    } else {
                        echo "No date selected.";
                        echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
                    }
                    ?>
                </div>

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>