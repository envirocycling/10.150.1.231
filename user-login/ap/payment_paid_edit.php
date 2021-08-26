<?php
session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';
$sql_payment = mysql_query("SELECT * FROM payment WHERE payment_id='" . $_GET['payment_id'] . "'");
$rs_payment = mysql_fetch_array($sql_payment);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <script type="text/javascript" src="js/payment/payReceiving.js"></script>
        <link rel="stylesheet" type="text/css" href="css/pay_table.css" />
        <link rel="stylesheet" type="text/css" href="css/pay_form.css" />
        <style>
            table{
                font-size: 15px;
                font-weight: bold;
            }
            h3{
                text-align: center;
            }

            .modalDialog {
                margin-top: -180px;
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
        <script>
            $(document).ready(function () {
                var date_now = "<?php echo date("Y/m/d"); ?>";
                var date_plus8d = "<?php echo date("Y/m/d", strtotime("+8 days", strtotime($rs_payment['date']))); ?>";
                if (date_now > date_plus8d) {
                    $("input").prop("readonly", true);
                    $("textarea").prop("disabled", true);
                    $(".submit").prop("disabled", true);
                    $("select").prop("disabled", true);
                    $("#err").html("<font color='red'>You can't edit this transaction.</font>");
                }

                $.c = 1;
                while ($.c <= 5) {
                    var ac_id = $("#ac_id_" + $.c).val();
                    $('#' + ac_id).each(function () {
                        var amount = $(this).find("#amount").html();
                        $("#limit_amount_" + $.c).val(amount);
                    });
                    $.c++;
                }
            });

        </script>
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
                        $trans_array = "";
                        $sub_total = 0;

                        $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_payment['supplier_id'] . "'");
                        $rs_sup = mysql_fetch_array($sql_sup);
                        echo "<h2>PAYMENT RECEIVED OF " . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</h2>";
                        echo "<br>";
                        echo "<table>";
                        echo "<tr>";
                        echo "<td>";
                        echo "<h3>Delivery</h3>";
                        echo '<div class="payTable">';
                        echo "<table width='450'>";
                        echo "<tr class='head'>";
                        echo "<td width='85'>Material</td>";
                        echo "<td>Net Weight</td>";
                        echo "<td>Cost</td>";
                        echo "<td>Amount</td>";
                        echo "</tr>";
                        $sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE payment_id='" . $rs_payment['payment_id'] . "'");
                        while ($rs_trans = mysql_fetch_array($sql_trans)) {
                            if ($trans_array == "") {
                                $trans_array = $rs_trans['trans_id'];
                                $tr = "scale_receiving.trans_id=" . $rs_trans['trans_id'];
                            } else {
                                $trans_array.="_" . $rs_trans['trans_id'];
                                $tr .= " or scale_receiving.trans_id=" . $rs_trans['trans_id'];
                            }
                        }
                        $sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE payment_id='" . $rs_payment['payment_id'] . "'");
                        $rs_trans = mysql_fetch_array($sql_trans);

                        $sql = "SELECT material_id, sum(corrected_weight) , price, sum(amount) FROM  `scale_receiving` INNER JOIN scale_receiving_details ON scale_receiving.trans_id = scale_receiving_details.trans_id WHERE " . $tr . " GROUP BY material_id, price";
                        $sql_details = mysql_query($sql);
                        while ($rs_details = mysql_fetch_array($sql_details)) {
                            $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_details['material_id'] . "'");
                            $rs_mat = mysql_fetch_array($sql_mat);
                            echo "<tr>";
                            echo "<td>" . $rs_mat['code'] . "</td>";
                            echo "<td>" . $rs_details['sum(corrected_weight)'] . "</td>";
                            echo "<td>" . $rs_details['price'] . "</td>";
                            echo "<td>" . $rs_details['sum(amount)'] . "</td>";
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
                        $sql = "SELECT *,group_concat(scale_receiving_details.detail_id), material_id, sum(corrected_weight) , price, sum(adj_amount) FROM  `scale_receiving` INNER JOIN scale_receiving_details ON scale_receiving.trans_id = scale_receiving_details.trans_id WHERE " . $tr . " GROUP BY material_id, price";
                        $sql_details = mysql_query($sql);
                        while ($rs_details = mysql_fetch_array($sql_details)) {
                            $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_details['material_id'] . "'");
                            $rs_mat = mysql_fetch_array($sql_mat);
                            echo "<input id='detail_id$c' type='hidden' name='detail_id$c' value='" . $rs_details['group_concat(scale_receiving_details.detail_id)'] . "'>";
                            echo "<input id='material_id$c' type='hidden' name='material_id$c' value='" . $rs_details['material_id'] . "'>";
                            echo "<tr>";
                            echo "<td>" . $rs_mat['code'] . "</td>";
                            echo "<td>" . round($rs_details['sum(corrected_weight)'], 2) . "<input type='hidden' id='adj_qty$c' name='adj_qty$c' size='17' value='" . round($rs_details['sum(corrected_weight)'], 2) . "' readonly></td>";
                            echo "<td><input class='medium-input' type='number' id='adj_price$c' name='adj_price$c' size='6' value='" . $rs_details['adj_price'] . "' onkeyup='adj();'></td>";
                            echo "<td><input class='medium-input' type='number' id='adj_amount$c' name='adj_amount$c' size='12' value='" . round($rs_details['sum(adj_amount)'], 2) . "' readonly></td>";
                            echo "</tr>";
                            $c++;
                        }
                        echo "</table>";
                        echo "</div>";

                        echo "<input type='hidden' id='del_adj_co' name='del_adj_co' value='$c'>";

                        echo "<br>";
                        echo "<h3>Other Adjustments</h3>";
                        echo '<div class="payTable">';
                        echo "<table width='450'>";
                        echo "<tr>";
                        echo "<td>Adjustments</td>";
                        echo "<td>Description</td>";
                        echo "<td>Amount</td>";
                        echo "</tr>";
                        echo "<tr>";
                        $ac_id_add_array = array();
                        $ac_id_ded_array = array();
                        $tp_id_array = array();
                        $ac_amount_add_array = array();
                        $ac_amount_ded_array = array();
                        $sql_adtl = '';
                        $sql_adtl_tp = '';
                        $ctr = 1;
                        $sql_adj = mysql_query("SELECT * FROM payment_adjustment WHERE payment_id='" . $rs_payment['payment_id'] . "'");
                        while ($rs_adj = mysql_fetch_array($sql_adj)) {

                            if ($rs_adj['ac_id'] != '0') {
                                if ($rs_adj['adj_type'] == 'add') {
                                    array_push($ac_id_add_array, $rs_adj['ac_id']);
                                    $ac_amount_add_array[$rs_adj['ac_id']] = $rs_adj['amount'];
                                }
                                if ($rs_adj['adj_type'] == 'deduct') {
                                    array_push($ac_id_ded_array, $rs_adj['ac_id']);
                                    $ac_amount_ded_array[$rs_adj['ac_id']] = $rs_adj['amount'];
                                }
                                if ($sql_adtl == '') {
                                    $sql_adtl.="ac_id!='" . $rs_adj['ac_id'] . "'";
                                } else {
                                    $sql_adtl.=" and ac_id!='" . $rs_adj['ac_id'] . "'";
                                }
                            }

                            if ($rs_adj['tp_id'] != '0') {
                                array_push($tp_id_array, $rs_adj['tp_id']);
                                if ($sql_adtl_tp == '') {
                                    $sql_adtl_tp.="truck_payment.tp_id!='" . $rs_adj['tp_id'] . "'";
                                } else {
                                    $sql_adtl_tp.=" and truck_payment.tp_id!='" . $rs_adj['tp_id'] . "'";
                                }
                            }

                            echo "<td>";
                            echo "<input id='adj_id_$ctr' class='total' type='hidden' name='adj_id_$ctr' value='" . $rs_adj['adj_id'] . "'>";
                            echo "<input type='hidden' id='ac_id_$ctr' name='ac_id_$ctr' value='" . $rs_adj['ac_id'] . "'>";
                            echo "<input type='hidden' id='tp_id_$ctr' name='tp_id_$ctr' value='" . $rs_adj['tp_id'] . "'>";
                            echo "<select id='adj_$ctr' class='medium-input' name='adj$ctr' onclick='adj();'>
                                    <option value='" . $rs_adj['adj_type'] . "'>" . strtoupper($rs_adj['adj_type']) . "</option>";
                            if ($rs_adj['ac_id'] == '') {
                                echo "<option value = ''></option>
                                <option value = 'add'>ADD</option>
                                <option value = 'deduct'>DEDUCT</option>";
                            }
                            echo "</select></td>";
                            echo "<td><input id='desc_$ctr' class='medium-input-2' type='text' name='desc$ctr' value='" . $rs_adj['desc'] . "' size='38'></td>";
                            if (($rs_adj['ac_id'] != '0' && $rs_adj['adj_type'] == 'add') || ($rs_adj['tp_id'] != '0' && $rs_adj['adj_type'] == 'deduct')) {
                                echo "<td><input id='amount_$ctr' class='medium-input' type='number' name='amount$ctr' value='" . $rs_adj['amount'] . "' size='9' onkeyup='checkLimit($ctr);' readonly></td>";
                                echo "<input id='limit_amount_$ctr' type='hidden' name='limit_amount$ctr' value='' size='9' readonly>";
                            } else {
                                echo "<td><input id='amount_$ctr' class='medium-input' type='number' name='amount$ctr' value='" . $rs_adj['amount'] . "' size='9' onkeyup='checkLimit($ctr);'></td>";
                                echo "<input id='limit_amount_$ctr' type='hidden' name='limit_amount$ctr' value='' size='9' readonly>";
                            }
                            echo "</tr>";
                            $ctr++;
                        }
                        while ($ctr <= 5) {
                            echo "<tr>";
                            echo "<td>";
                            echo "<input id = 'adj_id_$ctr' class = 'total' type = 'hidden' name = 'adj_id_$ctr' value = ''>";
                            echo "<input type='hidden' id='ac_id_$ctr' name='ac_id_$ctr' value=''>";
                            echo "<input type='hidden' id='tp_id_$ctr' name='tp_id_$ctr' value=''>";
                            echo "<select id = 'adj_$ctr' class = 'medium-input' name = 'adj$ctr' onclick = 'adj();'>
                            <option value = ''></option>
                            <option value = 'add'>ADD</option>
                            <option value = 'deduct'>DEDUCT</option>
                            </select></td>";
                            echo "<td><input id = 'desc_$ctr' class = 'medium-input-2' type = 'text' name = 'desc$ctr' value = '' size = '38'></td>";
                            echo "<td><input id = 'amount_$ctr' class = 'medium-input' type = 'number' name = 'amount$ctr' value = '' size = '9' onkeyup = 'adj();'></td>";
                            echo "</tr>";
                            $ctr++;
                        }
                        echo "<tr>";
                        echo "<td colspan='4' class='head' style='vertical-align: top;'>Remarks
                            <br>
                            <textarea id='remarks' class='medium-textarea-2' name='remarks' placeholder='Remarks..' required></textarea>
                            <br>
                            Charge to:
                            <br>
                            <select id='charge_to' class='medium-input' name='charge'>
                            <option value='" . $_SESSION['user_id'] . "'>AP</option>
                            <option value='Supplier'>Supplier</option>
                            </select>
                            </td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";

                        function actyName($acty_id) {
                            $sql_acty = mysql_query("SELECT * FROM adv_type WHERE acty_id = '$acty_id'");
                            $rs_acty = mysql_fetch_array($sql_acty);
                            return $rs_acty['name'];
                        }

                        function refNo($ac_id, $type) {
                            $sql_adv = mysql_query("SELECT * FROM adv WHERE ac_id='$ac_id'");
                            $rs_adv = mysql_fetch_array($sql_adv);

                            if ($rs_adv['acpty_id'] == '3') {
                                if ($rs_adv['payment_id'] == 0) {
                                    $cheque_no = "";
                                    $voucher_no = "SBC_PAM" . $rs_adv['voucher_no'];
                                } else {
                                    $sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $rs_adv['payment_id'] . "'");
                                    $rs_pay = mysql_fetch_array($sql_pay);

                                    $sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
                                    $rs_code = mysql_fetch_array($sql_code);

                                    $cheque_no = "";
                                    $voucher_no = "SBC_" . $rs_code['code'] . "" . $rs_pay['voucher_no'];
                                }
                            } else if ($rs_adv['acpty_id'] == '2') {
                                if ($rs_adv['payment_id'] == 0) {
                                    $cheque_no = $rs_adv['cheque_no'];
                                    $voucher_no = $rs_adv['voucher_no'];
                                } else {
                                    $sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $rs_adv['payment_id'] . "'");
                                    $rs_pay = mysql_fetch_array($sql_pay);
                                    $cheque_no = $rs_pay['cheque_no'];
                                    $voucher_no = $rs_pay['voucher_no'];
                                }
                            } else {
                                $cheque_no = "";
                                $voucher_no = $rs_adv['ac_no'];
                            }
                            if ($type == 'voucher_no') {
                                return $voucher_no;
                            } else {
                                return $cheque_no;
                            }
                        }

                        function checkBal($ac_id) {
                            $sql_adv_less = mysql_query("SELECT sum(amount) FROM payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='$ac_id' and payment_adjustment.adj_type='deduct' and payment.status!='cancelled' and payment.status!='deleted'");
                            $rs_adv_less = mysql_fetch_array($sql_adv_less);

                            $sql_adv_pay = mysql_query("SELECT sum(amount) FROM adv_payment WHERE ac_id='" . $ac_id . "' and status!='cancelled'");
                            $rs_adv_pay = mysql_fetch_array($sql_adv_pay);

                            $balance = $rs_adv_pay['sum(amount)'] + $rs_adv_less['sum(amount)'];

                            return $balance;
                        }

                        $count = count($ac_id_add_array);

                        $sql_adv = mysql_query("SELECT * FROM adv WHERE supplier_id = '" . $rs_sup['id'] . "' and acpty_id!='1' and status = 'approved'");
                        $rs_adv_c = mysql_num_rows($sql_adv);
                        $count+=$rs_adv_c;
                        if ($count > 0) {
                            echo "<br>";
                            echo "<h3>For Issuing Advances</h3>";
                            echo '<div class="payTable">';

                            echo "<table width = '450'>";
                            echo "<tr>";
                            echo "<td>Date</td>";
                            echo "<td>Adv ID</td>";
                            echo "<td>Type</td>";
                            echo "<td>Amount</td>";
                            echo "<td>Action</td>";
                            echo "</tr>";

                            foreach ($ac_id_add_array as $ac_id) {
                                $sql_data = mysql_query("SELECT * FROM adv WHERE ac_id='$ac_id'");
                                $rs_data = mysql_fetch_array($sql_data);

                                echo "<tr id = '$ac_id'>";
                                echo "<td id = 'date'>" . date("Y/m/d", strtotime($rs_data['date'])) . "</td>";
                                echo "<td id = 'ac_no'>" . $rs_data['ac_no'] . "</td>";
                                echo "<td id = 'type'>" . actyName($rs_data['acty_id']) . "</td>";
                                echo "<td id = 'amount'>" . $ac_amount_add_array[$ac_id] . "</td>";

                                echo "<td>";
                                echo "<input type = 'button' id = 'r_" . $rs_data['ac_id'] . "' class = 'submit' onclick = 'advances(this.id);' value = 'Remove'>";
                                echo "</td>";
                                echo "</tr>";
                            }

                            while ($rs_adv = mysql_fetch_array($sql_adv)) {
                                echo "<tr id = '" . $rs_adv['ac_id'] . "'>";
                                echo "<td id='date'>" . date("Y/m/d", strtotime($rs_adv['date'])) . "</td>";
                                echo "<td id='ac_no'>" . $rs_adv['ac_no'] . "</td>";
                                echo "<td id = 'type'>" . actyName($rs_adv['acty_id']) . "</td>";
                                echo "<td id='amount'>" . round($rs_adv['amount'], 2) . "</td>";
                                echo "<td><input type = 'button' id = 'a_" . $rs_adv['ac_id'] . "' class = 'submit' onclick = 'advances(this.id);' value = 'Add'></td>";
                                echo "</tr>";
                            }

                            echo "</table>";
                            echo '</div>';
                        }
                        $sql_adtl_adv = '';
                        $count = count($ac_id_ded_array);
                        $count2 = count($tp_id_array);
                        if ($sql_adtl != '') {
                            $sql_adtl_adv = "and (" . $sql_adtl . ")";
                        }

                        $sql_adtl_truckRental = '';
                        if ($sql_adtl_tp != '') {
                            $sql_adtl_truckRental = "and (" . $sql_adtl_tp . ")";
                        }

                        $sql_adv = mysql_query("SELECT * FROM adv WHERE supplier_id = '" . $rs_sup['id'] . "' and status = 'issued' $sql_adtl_adv");
                        $rs_adv_c = mysql_num_rows($sql_adv);

                        $sql_truckRental = mysql_query("SELECT truck_payment.tp_id as tp_id, truck_payment.pay_name as pay_name, truck_payment.amount as amount, truck_monitoring.plate_no as plate_no, truck_payment.type as type, truck_payment.month as month FROM truck_payment INNER JOIN truck_monitoring ON truck_payment.tr_id=truck_monitoring.tr_id WHERE truck_monitoring.supplier_id='" . $rs_sup['id'] . "' and truck_payment.status='' $sql_adtl_truckRental");
                        $count_truckRental = mysql_num_rows($sql_truckRental);

                        $total_count = $count + $count2 + $rs_adv_c + $count_truckRental;

                        $count+=$rs_adv_c;

                        if ($count > 0) {
                            echo "<br>";

                            echo "<h3>For Deduction Advances</h3>";
                            echo '<div class="payTable">';

                            echo "<table width = '450'>";
                            echo "<tr>";
                            echo "<td>Ref No.</td>";
                            echo "<td>Cheque No.</td>";
                            echo "<td>Type</td>";
                            echo "<td>Amount</td>";
                            echo "<td>Action</td>";
                            echo "</tr>";


                            foreach ($ac_id_ded_array as $ac_id) {
                                $sql_data = mysql_query("SELECT * FROM adv WHERE ac_id='$ac_id'");
                                $rs_data = mysql_fetch_array($sql_data);

                                $bal = checkBal($ac_id);
                                $total_bal = $rs_data['amount'] - $bal;

                                echo "<tr id = '$ac_id'>";
                                echo "<td id = 'voucher_no'>" . refNo($ac_id, 'voucher_no') . "</td>";
                                echo "<td id = 'cheque_no'>" . refNo($ac_id, 'cheque_no') . "</td>";
                                echo "<td id = 'type'>" . actyName($rs_data['acty_id']) . "</td>";
                                echo "<td id = 'amount'>" . round($total_bal + $ac_amount_ded_array[$ac_id], 2) . "</td>";
                                echo "<td>";
                                echo "<input type = 'button' id = 'r_" . $rs_data['ac_id'] . "' class = 'submit' onclick = 'advances(this.id);' value = 'Remove'>";
                                echo "</td>";
                                echo "</tr>";
                            }

                            while ($rs_adv = mysql_fetch_array($sql_adv)) {

                                $bal = checkBal($rs_adv['ac_id']);
                                $total_bal = $rs_adv['amount'] - $bal;

                                echo "<tr id = '" . $rs_adv['ac_id'] . "'>";
                                echo "<td id = 'voucher_no'>" . refNo($rs_adv['ac_id'], 'voucher_no') . "</td>";
                                echo "<td id = 'cheque_no'>" . refNo($rs_adv['ac_id'], 'cheque_no') . "</td>";
                                echo "<td id = 'type'>" . actyName($rs_adv['acty_id']) . "</td>";
                                echo "<td id = 'amount'>" . round($total_bal, 2) . "</td>";

                                echo "<td><input type = 'button' id = 'd_" . $rs_adv['ac_id'] . "' class = 'submit' onclick = 'advances(this.id);' value = 'Deduct'></td>";
                                echo "</tr>";
                            }

                            foreach ($tp_id_array as $tp_id) {
                                $sql_tp = mysql_query("SELECT truck_payment.tp_id as tp_id, truck_payment.pay_name as pay_name, truck_payment.amount as amount, truck_monitoring.plate_no as plate_no, truck_payment.type as type FROM truck_payment INNER JOIN truck_monitoring ON truck_payment.tr_id=truck_monitoring.tr_id WHERE truck_payment.tp_id='$tp_id'");
                                $rs_tp = mysql_fetch_array($sql_tp);
                                echo "<tr id='" . $rs_tp['tp_id'] . "'>";
                                echo "<td colspan='2' id='data'>" . $rs_tp['pay_name'] . " - " . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . " (" . $rs_tp['plate_no'] . ")</td>";
                                echo "<td id='type'>TRUCK<br>" . strtoupper($rs_tp['type']) . "</td>";
                                echo "<td id='amount'>" . round($rs_tp['amount'], 2) . "</td>";
                                echo "<td><input type='button' id='r_" . $rs_tp['tp_id'] . "' class='submit' onclick='truckRental(this.id);' value='Remove'></td>";
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

                        echo "</td>";
                        echo "<td style='vertical-align: top;'>";
                        echo "<table>";
                        echo "<tr>";
                        echo "<td class='head'>Sub Total: </td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><input id='sub_total' class='medium-input-money' type='text' name='sub_total' value='" . $rs_payment['sub_total'] . "' size='15' readonly></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td class='head'>Weighing Fee: </td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><input id='ts_fee' class='medium-input-money' type='text' name='ts_fee' value='" . $rs_payment['ts_fee'] . "' size='15' readonly></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td class='head'>Adjustments: </td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><input id='adjustments' class='medium-input-money' type='text' name='adj' value='" . $rs_payment['adjustments'] . "' size='15' readonly></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td class='head'>Grand Total: </td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><input id='grand_total' class='medium-input-money' type='text' name='grand_total' value='" . $rs_payment['grand_total'] . "' size='15' readonly></td>";
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
                        echo "<br>";
                        echo "<table border=0' width='550'>";
                        echo "<tr>";
                        echo "<td>&nbsp;&nbsp;";
                        echo "</td>";
                        echo "<td align='right'>";

                        echo "<form id='myform' name='myform' action='payment_paid_edit_next.php' method='POST'>";
                        echo "<input type='hidden' name='payment_id' value='" . $rs_payment['payment_id'] . "'>";
                        echo "<input id='charge_to_s' type='hidden' name='charge_to' value='" . $rs_payment['charge_to'] . "'>";
                        echo "<input id='remarks_s' type='hidden' name='remarks' value='" . $rs_payment['remarks'] . "'>";
//                        echo "<input type='hidden' name='bank_code' value='" . $rs_payment['bank_code'] . "'>";
//                        echo "<input type='hidden' name='cheque_name' value='" . $rs_payment['cheque_name'] . "'>";
                        echo "<input type='hidden' name='c_cheque_no' value='" . $rs_payment['cheque_no'] . "'>";
                        echo "<input id='supplier_id' type='hidden' name='supplier_id' value='" . $rs_payment['supplier_id'] . "'>";
                        echo "<input type='hidden' name='sup_id' value='" . $rs_sup['id'] . "'>";
                        echo "<input type='hidden' name='sup_name' value='" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "'>";
                        echo "<input type='hidden' name='trans_id' value='$trans_array'>";
                        echo "</form>";

                        echo "<button class='large-submit' onclick='enter();'>Next</button>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
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
                    <iframe src="template/pending2.php" width="367" height="1000" scrolling="yes"></iframe>
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
        <iframe src="view_price.php?supplier_id=<?php echo $rs_trans['supplier_id']; ?>&date=<?php echo $rs_trans['date']; ?>" width="400" height="480" scrolling="yes"></iframe>
    </div>
</div>