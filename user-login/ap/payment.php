<?php
session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <script type="text/javascript" src="js/payment/payReceiving.js"></script>
        <script>
            $(document).ready(function(){
                $('#mark_paid').click(function(){
                    var con = confirm('Are you sure you want to mark as paid?');
                    if(con === true){
                      var mark_paid = prompt("Please enter the check/sbc voucher number where this transaction paid. When you click ok you cannot undo this action. Thanks!");  
                        if(mark_paid != null){
                            var trans_ids = $('#trans_ids').val();
                            location.replace("mark_as_paid.php?trans_id=" + trans_ids + "&ref=" + mark_paid);
                        }
                    }
                });
            });
        </script>
        <link rel="stylesheet" type="text/css" href="css/pay_table.css" />
        <link rel="stylesheet" type="text/css" href="css/pay_form.css" />
        <style>
            input[type=number]::-webkit-inner-spin-button, 
            input[type=number]::-webkit-outer-spin-button { 
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                margin: 0; 
            }
            table{
                font-size: 15px;
                font-weight: bold;
            }
            h3{
                text-align: center;
            }
            .load{
                position: absolute;
            }

            .modalDialog {
                margin-top: 0px;
                margin-left: -890px;
                position: fixed;
                font-family: Arial, Helvetica, sans-serif;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                /*background: rgba(0, 0, 0, 0.8);*/
                z-index: 99999;
                opacity:0;
                -webkit-transition: opacity 400ms ease-in;
                -moz-transition: opacity 400ms ease-in;
                transition: opacity 400ms ease-in;
                pointer-events: none; 
            }
            .modalDialog:target {
                opacity:1;
                pointer-events: auto;
            }
            .modalDialog > div {
                height: 500px;
                width: 400px;
                position: relative;
                margin: 10% auto;
                padding: 5px 20px 13px 20px;
                border-radius: 10px;
                background: #fff;
                background: -moz-linear-gradient(#fff, #999);
                background: -webkit-linear-gradient(#fff, #999);
                background: -o-linear-gradient(#fff, #999);
            }
            .close {
                background: #606061;
                color: #FFFFFF;
                line-height: 25px;
                position: absolute;
                right: -12px;
                text-align: center;
                top: -10px;
                width: 24px;
                text-decoration: none;
                font-weight: bold;
                -webkit-border-radius: 12px;
                -moz-border-radius: 12px;
                border-radius: 12px;
                -moz-box-shadow: 1px 1px 3px #000;
                -webkit-box-shadow: 1px 1px 3px #000;
                box-shadow: 1px 1px 3px #000;
            }
            .close:hover {
                background: #00d9ff;
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
                        <div style="margin-left: -10px;">
                            <?php
                            include 'template/menu.php';
                            ?>
                        </div>
                        <br>
                        <?php
                        $receiving = array();
                        $trans_id = array();
                        $sub_total = 0;
                        $trans = "0";
                        $ts_fee = 0;
                        $trans_array = "";
                        $sql_pending = mysql_query("SELECT * FROM scale_receiving WHERE status='generated'");
                        while ($rs_pending = mysql_fetch_array($sql_pending)) {
                            if (!empty($_POST[$rs_pending['trans_id']])) {
                                $trans = $rs_pending['trans_id'];
                                array_push($trans_id, $rs_pending['trans_id']);
                                array_push($receiving, $rs_pending['supplier_id']);
                            }
                        }
                        $unique = array_unique($receiving);
                        $count = count($unique);

                        if ($count != 1) {
                            echo "<script>
                                alert('Error.');
                                history.back();
                                </script>";
                        } else {
                            $sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='$trans'");
                            $rs_trans = mysql_fetch_array($sql_trans);
                            $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='$unique[0]'");
                            $rs_sup = mysql_fetch_array($sql_sup);

                            $ctr = 0;
                            foreach ($trans_id as $pay) {
                                if ($trans_array == "") {
                                    $trans_array = $pay;
                                    $tr = "scale_receiving.trans_id=" . $pay;
                                } else {
                                    $trans_array.="_" . $pay;
                                    $tr .= " or scale_receiving.trans_id=" . $pay;
                                }

                                mysql_query("UPDATE `scale_receiving` SET `edit_price`='0' WHERE trans_id='$pay'");

                                $sql_ttrans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id ='$pay'");
                                $rs_ttrans = mysql_fetch_array($sql_ttrans);

                                $sql_count = mysql_query("SELECT count(trans_id) FROM scale_receiving_details WHERE trans_id='$pay'");
                                $rs_count = mysql_fetch_array($sql_count);

//                                $sql_fee = mysql_query("SELECT * FROM ts_fee WHERE fee_id='" . $rs_ttrans['ts_fee'] . "'");
//                                $rs_fee = mysql_fetch_array($sql_fee);
//
//                                $sql_ts_disc = mysql_query("SELECT * FROM payment_settings");
//                                $rs_ts_disc = mysql_fetch_array($sql_ts_disc);
//
//                                if ($rs_ts_disc['ts_fee_discount'] == 'on') {
//                                    $ctr = $rs_count['count(trans_id)'];
//                                    $count = $ctr - 1;
//                                    $fee_discount = $rs_fee['price'] / 2;
//                                    $ts_fee_discount = $fee_discount * $count;
//                                    $ts_fee+= $rs_fee['price'] + $ts_fee_discount;
//                                } else {
//                                    $ctr = $rs_count['count(trans_id)'];
//                                    $ts_fee += $rs_fee['price'] * $ctr;
//                                }

                                $ts_fee = 0;
                            }

                            echo "<h2>PAYMENT FOR THE FOLLOWING DELIVERIES OF " . $rs_sup['id'] . "_" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</h2>";
                            echo "<br>";
                            echo "<input id='click' type='hidden' name='click' value='0'>";
                            echo "<table border='0'>";
                            echo "<tr>";
                            echo "<td style='vertical-align: top;'>";
                            if ($ctr > 1) {
                                echo "<h3>Deliveries</h3>";
                            } else {
                                echo "<h3>Delivery</h3>";
                            }
                            echo '<div class="payTable">';
                            echo "<table width='450'>";
                            echo "<tr class='head'>";
                            echo "<td width='85'>Material</td>";
                            echo "<td>Net Weight</td>";
                            echo "<td>Cost</td>";
                            echo "<td>Amount</td>";
                            echo "</tr>";

                            $sql = "SELECT * FROM `scale_receiving` INNER JOIN scale_receiving_details ON scale_receiving.trans_id = scale_receiving_details.trans_id WHERE " . $tr . "";
                            $sql_details = mysql_query($sql);
                            while ($rs_details = mysql_fetch_array($sql_details)) {
								$dt_id = $rs_details['dt_id'];
                                if ($dt_id == '1' || $dt_id == '2') {
                                    $dt_id = '1';
                                }

                                $sql_sup_price = mysql_query("SELECT * FROM suppliers_price WHERE dt_id='$dt_id' and material_id='" . $rs_details['material_id'] . "' and supplier_id='" . $rs_trans['supplier_id'] . "' and date<='" . $rs_details['date'] . "' ORDER BY date DESC");
                                $rs_sup_price_count = mysql_num_rows($sql_sup_price);
                                $rs_sup_price = mysql_fetch_array($sql_sup_price);
								

                                $sql_def_price = mysql_query("SELECT price FROM default_price WHERE material_id='" . $rs_details['material_id'] . "'");
                                $rs_def_price = mysql_fetch_array($sql_def_price);

                                if ($rs_sup_price_count == 0) {
                                    $amount = $rs_def_price['price'] * $rs_details['corrected_weight'];
                                    mysql_query("UPDATE scale_receiving_details SET price='" . $rs_def_price['price'] . "',amount='$amount' WHERE detail_id='" . $rs_details['detail_id'] . "'");
									
                                } else {
                                    $amount = $rs_sup_price['price'] * $rs_details['corrected_weight'];
                                    mysql_query("UPDATE scale_receiving_details SET price='" . $rs_sup_price['price'] . "',amount='$amount' WHERE detail_id='" . $rs_details['detail_id'] . "'");
                                }
                            }

                            $sql = "SELECT material_id, sum(corrected_weight) , price, sum(amount) FROM  `scale_receiving` INNER JOIN scale_receiving_details ON scale_receiving.trans_id = scale_receiving_details.trans_id WHERE " . $tr . " GROUP BY material_id, price";
                            $sql_details = mysql_query($sql);
                            while ($rs_details = mysql_fetch_array($sql_details)) {
                                $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_details['material_id'] . "'");
                                $rs_mat = mysql_fetch_array($sql_mat);
                                echo "<tr>";
                                echo "<td>" . $rs_mat['code'] . "</td>";
                                echo "<td>" . round($rs_details['sum(corrected_weight)'], 2) . "</td>";
                                echo "<td>" . $rs_details['price'] . "</td>";
                                echo "<td>" . round($rs_details['sum(amount)'], 2) . "</td>";
                                $sub_total+=$rs_details['sum(amount)'];
                                echo "</tr>";
                            }
                            echo "</table>";

                            echo "</div>";
                            echo '<center><a href="#openModal" id="viewPrice">View Prices</a></center>';

                            echo "<br>";
                            echo "<h3>Delivery Adjustments</h3>";
                            echo '<div class="payTable">';
                            echo "<table width='450'>";
                            echo "<tr class='head'>";
                            echo "<td width='85'>Material</td>";
                            echo "<td width='150'>Net Weight</td>";
                            echo "<td>Cost</td>";
                            echo "<td>Amount</td>";
                            echo "</tr>";

                            $c = 1;
                            $sql = "SELECT group_concat(scale_receiving_details.detail_id), material_id, sum(corrected_weight) , price, sum(amount) FROM  `scale_receiving` INNER JOIN scale_receiving_details ON scale_receiving.trans_id = scale_receiving_details.trans_id WHERE " . $tr . " GROUP BY material_id, price";
                            $sql_details = mysql_query($sql);
                            while ($rs_details = mysql_fetch_array($sql_details)) {
                                $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_details['material_id'] . "'");
                                $rs_mat = mysql_fetch_array($sql_mat);
                                echo "<input id='detail_id$c' type='hidden' name='detail_id$c' value='" . $rs_details['group_concat(scale_receiving_details.detail_id)'] . "'>";
                                echo "<input id='material_id$c' type='hidden' name='material_id$c' value='" . $rs_details['material_id'] . "'>";
                                echo "<tr>";
                                echo "<td>" . $rs_mat['code'] . "</td>";
                                echo "<td>" . round($rs_details['sum(corrected_weight)'], 2) . "<input type='hidden' id='adj_qty$c' name='adj_qty$c' size='17' value='" . round($rs_details['sum(corrected_weight)'], 2) . "' readonly></td>";
                                echo "<td><input class='medium-input' type='number' id='adj_price$c' name='adj_price$c' size='6' value='' onkeyup='adj();'></td>";
                                echo "<td><input class='medium-input' id='adj_amount$c' name='adj_amount$c' size='12' value='' readonly></td>";
                                echo "</tr>";
                                $c++;
                            }
                            echo "</table>";
                            echo "</div>";

                            echo "<input type='hidden' id='del_adj_co' name='del_adj_co' value='$c'>";

                            echo "<br>";

                            echo "<h3>Adjustments</h3>";
                            echo '<div class="payTable">';
                            echo "<table width='550'>";
                            echo "<tr>";
                            echo "<td width='80'>Type</td>";
                            echo "<td>Description</td>";
                            echo "<td>Amount</td>";
                            echo "</tr>";
                            $c = 1;
                            while ($c <= 5) {
                                echo "<tr>";
                                echo "<td><input type='hidden' id='ac_id_$c' name='ac_id_$c' value=''>
                                    <input type='hidden' id='tp_id_$c' name='tp_id_$c' value=''>
                                    <input type='hidden' id='adj_id_$c' name='adj_id_$c' value=''>
                                <select id='adj_$c' class='medium-input' name='adj$c' onclick='adj();'>
                                    <option value=''></option>
                                    <option value='add'>ADD</option>
                                    <option value='deduct'>DEDUCT</option>
                                    </select></td>";
                                echo "<td><input id='desc_$c' class='medium-input-2' type='text' name='desc$c' value=''></td>";
                                echo "<td>";
                                echo "<input id='amount_$c' class='medium-input' type='number' name='amount$c' value='' size='9' onkeyup='checkLimit($c);'>";
                                echo "<input id='limit_amount_$c' type='hidden' name='limit_amount$c' value='' size='9' readonly>";
                                echo "</td>";

                                echo "</tr>";
                                $c++;
                            }
                            echo "</table>";
                            echo '</div>';

                            $sql_adv = mysql_query("SELECT * FROM adv WHERE supplier_id='" . $rs_sup['id'] . "' and status='approved' and acpty_id!='1'");
                            $rs_adv_c = mysql_num_rows($sql_adv);
                            if ($rs_adv_c > 0) {
                                echo "<br>";

                                echo "<h3>For Issuing Advances</h3>";
                                echo '<div class="payTable">';
                                echo "<table width='550'>";
                                echo "<tr>";
                                echo "<td>Date</td>";
                                echo "<td>Adv ID</td>";
                                echo "<td>Type</td>";
                                echo "<td>Amount</td>";
                                echo "<td>Action</td>";
                                echo "</tr>";
                                while ($rs_adv = mysql_fetch_array($sql_adv)) {
                                    $sql_acty = mysql_query("SELECT * FROM adv_type WHERE acty_id='" . $rs_adv['acty_id'] . "'");
                                    $rs_acty = mysql_fetch_array($sql_acty);

                                    echo "<tr id='" . $rs_adv['ac_id'] . "'>";
                                    echo "<td id='date'>" . date("Y/m/d", strtotime($rs_adv['date'])) . "</td>";
                                    echo "<td id='ac_no'>" . $rs_adv['ac_no'] . "</td>";
                                    echo "<td id='type'>" . $rs_acty['name'] . "</td>";
                                    echo "<td id='amount'>" . round($rs_adv['amount'], 2) . "</td>";
                                    echo "<td><input type='button' id='a_" . $rs_adv['ac_id'] . "' class='submit' onclick='advances(this.id);' value='Add'></td>";
                                    echo "</tr>";
                                }

                                echo "</table>";
                                echo '</div>';
                            }
                            $sql_adv = mysql_query("SELECT * FROM adv WHERE supplier_id='" . $rs_sup['id'] . "' and status='issued'");
                            $rs_adv_c = mysql_num_rows($sql_adv);


                            $sql_truckRental = mysql_query("SELECT truck_payment.tp_id as tp_id, truck_payment.pay_name as pay_name, truck_payment.amount as amount, truck_monitoring.plate_no as plate_no, truck_payment.type as type, truck_payment.month as month FROM truck_payment INNER JOIN truck_monitoring ON truck_payment.tr_id=truck_monitoring.tr_id WHERE truck_monitoring.supplier_id='" . $rs_sup['id'] . "' and truck_payment.status=''");
                            $count_truckRental = mysql_num_rows($sql_truckRental);

                            $count = $rs_adv_c + $count_truckRental;
                            if ($count > 0) {
                                echo "<br>";
                                if ($count > 1) {
                                    echo "<h3>For Deductions</h3>";
                                } else {
                                    echo "<h3>For Deduction</h3>";
                                }
                                echo '<div class="payTable">';
                                echo "<table width='550'>";
                                echo "<tr>";
                                echo "<td>Ref No.</td>";
                                echo "<td>Cheque No.</td>";
                                echo "<td>Type</td>";
                                echo "<td>Amount</td>";
                                echo "<td>Action</td>";
                                echo "</tr>";
                                while ($rs_adv = mysql_fetch_array($sql_adv)) {

                                    $sql_acty = mysql_query("SELECT * FROM adv_type WHERE acty_id='" . $rs_adv['acty_id'] . "'");
                                    $rs_acty = mysql_fetch_array($sql_acty);

                                    $sql_adv_less = mysql_query("SELECT sum(amount) FROM payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='" . $rs_adv['ac_id'] . "' and payment_adjustment.adj_type='deduct' and payment.status!='cancelled' and payment.status!='deleted'");
                                    $rs_adv_less = mysql_fetch_array($sql_adv_less);


                                    $sql_adv_pay = mysql_query("SELECT sum(amount) FROM adv_payment WHERE ac_id='" . $rs_adv['ac_id'] . "' and status!='cancelled'");
                                    $rs_adv_pay = mysql_fetch_array($sql_adv_pay);

                                    $total_less = $rs_adv_pay['sum(amount)'] + $rs_adv_less['sum(amount)'];

                                    if ($rs_adv['acpty_id'] == '3') {
                                        if ($rs_adv['payment_id'] == 0) {
                                            $cheque_no = "";
                                            $ref_no = "SBC_PAM" . $rs_adv['voucher_no'];
                                        } else {
                                            $sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $rs_adv['payment_id'] . "'");
                                            $rs_pay = mysql_fetch_array($sql_pay);

                                            $sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
                                            $rs_code = mysql_fetch_array($sql_code);

                                            $cheque_no = "";
                                            $ref_no = "SBC_" . $rs_code['code'] . "" . $rs_pay['voucher_no'];
                                        }
                                    } else if ($rs_adv['acpty_id'] == '2') {
                                        if ($rs_adv['payment_id'] == 0) {
                                            $cheque_no = $rs_adv['cheque_no'];
                                            $ref_no = $rs_adv['voucher_no'];
                                        } else {
                                            $sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $rs_adv['payment_id'] . "'");
                                            $rs_pay = mysql_fetch_array($sql_pay);
                                            $cheque_no = $rs_pay['cheque_no'];
                                            $ref_no = $rs_pay['voucher_no'];
                                        }
                                    } else {
                                        $cheque_no = "";
                                        $ref_no = $rs_adv['ac_no'];
                                    }

                                    echo "<tr id='" . $rs_adv['ac_id'] . "'>";
                                    echo "<td id='voucher_no'>$ref_no</td>";
                                    echo "<td id='cheque_no'>$cheque_no</td>";
                                    echo "<td id='type'>" . $rs_acty['name'] . "</td>";
                                    echo "<td id='amount'>" . round($rs_adv['amount'] - $total_less, 2) . "</td>";
                                    echo "<td><input type='button' id='d_" . $rs_adv['ac_id'] . "' class='submit' onclick='advances(this.id);' value='Deduct'></td>";
                                    echo "</tr>";
                                }

                                while ($rs_truckRental = mysql_fetch_array($sql_truckRental)) {
                                    echo "<tr id='" . $rs_truckRental['tp_id'] . "'>";
                                    echo "<td colspan='2' id='data'>" . $rs_truckRental['pay_name'] . " - " . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . " (" . $rs_truckRental['plate_no'] . ") " . strtoupper(date("M Y", strtotime($rs_truckRental['month']))) . "</td>";
                                    echo "<td id='type'>TRUCK<br>" . strtoupper($rs_truckRental['type']) . "</td>";
                                    echo "<td id='amount'>" . round($rs_truckRental['amount'], 2) . "</td>";
                                    echo "<td><input type='button' id='d_" . $rs_truckRental['tp_id'] . "' class='submit' onclick='truckRental(this.id);' value='Deduct'></td>";
                                    echo "</tr>";
                                }


                                echo "</table>";
                                echo '</div>';
                            }

                            echo "<br>";
                            echo "<table border='0' width='550'>";
                            echo "<tr>";
                            echo "<td>";
                            ?>
                            <!--<a href="mark_as_paid.php?trans_id=<?php //echo $trans_array; ?>" onClick="return confirm('Are you sure you want to mark as paid? When you click ok you cant undo this action. Thanks!')">--><button id="mark_paid" class='large-submit'>Paid</button></a>
                            <?php
                            echo '<input type="hidden" value="'.$trans_array.'" id="trans_ids">';
                            echo "</td>";

                            echo "<form id='myform' name='myform' onsubmit='return OnSubmitForm();' method='POST'>";
                            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";


                            echo '<input type="radio" class="large-radio" name="operation" value="1" checked> <font size="4">Cheque</font>';
                            echo '<input type="radio" class="large-radio" name="operation" value="2"> <font size="4">Digibanker</font>';

                            echo "</td>";
                            echo "<td align='right'>";
                            echo "<input id='supplier_id' type='hidden' name='supplier_id' value='$unique[0]'>";
                            echo "<input type='hidden' name='sup_id' value='" . $rs_sup['id'] . "'>";
                            echo "<input type='hidden' name='sup_name' value='" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "'>";
                            echo "<input type='hidden' name='trans_id' value='$trans_array'>";
                            echo "</form>";
                            echo "<button class='large-submit' onclick='enter();'>Next</button>";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";

                            echo "</td>";
                            echo "<td style='vertical-align: top;'>";
                            echo "<table>";

                            echo "<tr>";
                            echo "<td class='head'>Sub Total: </td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><input id='sub_total' class='medium-input-money' type='text' name='sub_total' value='" . round($sub_total, 2) . "' size='8' readonly></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td class='head'>Weighing Fee: </td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><input id='ts_fee' class='medium-input-money' type='text' name='ts_fee' value='" . round($ts_fee, 2) . "' size='8' readonly></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td class='head'>Adjustments: </td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><input id='adjustments' class='medium-input-money' type='text' name='adj' value='' size='8' readonly></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td class='head'>Grand Total: </td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><input id='grand_total' class='medium-input-money' type='text' name='grand_total' value='" . round($sub_total - $ts_fee, 2) . "' size='8' readonly></td>";
                            echo "</tr>";
                            echo "</table>";
                            echo "</td>";

                            echo "</tr>";
                            echo "</table>";
                            echo "</td>";
                            echo "<td>";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                        }
                        ?>
                        <table>
                            <tr>
                                <td>
                                    <div id="err"></div>
                                </td>
                            </tr>
                        </table>
                        <br><br>
                    </main><!-- .content -->
                </div><!-- .container-->

                <aside class="left-sidebar">
                    <iframe src="template/pending2.php" width="367" height="1200" scrolling="yes"></iframe>
                </aside><!-- .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>

<div id="openModal" class="modalDialog">
    <div>
        <a href="#close" title="Close" class="close">X</a>
        <h2><?php echo $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name']; ?> as of <?php echo date("M d, Y", strtotime($rs_trans['date'])); ?></h2>
        <iframe src="view_price.php?supplier_id=<?php echo $rs_trans['supplier_id']; ?>&date=<?php echo $rs_trans['date']; ?>&dt_id=<?php echo $rs_trans['dt_id']; ?>" width="400" height="480" scrolling="yes"></iframe>
    </div>
</div>