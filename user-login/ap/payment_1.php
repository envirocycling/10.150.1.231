<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}

//$voucher_date = date("md");
//$date = date("Y/m/d");
//$sql_voucher = mysql_query("SELECT count(voucher_no),max(voucher_no) FROM payment WHERE date='$date'");
//$rs_voucher = mysql_fetch_array($sql_voucher);
//if ($rs_voucher['count(voucher_no)'] == '0') {
//    $voucher_number = "01";
//} else {
//    $details = preg_split("[-]", $rs_voucher['max(voucher_no)']);
//    $voucher_number = $details[1] + 1;
//    if ($voucher_number < 10) {
//        $voucher_number = "0" . $voucher_number;
//    }
//}

if (!isset($_SESSION['verifier'])) {
    echo "<script>
    alert('Verifier is not set Please go to settings and update the setup.');
    location.replace('initial_settings.php');
    </script>";
}
if (!isset($_SESSION['signatory'])) {
    echo "<script>
    alert('Verifier is not set Please go to settings and update the setup.');
    location.replace('initial_settings.php');
    </script>";
}

$sql_online = mysql_query("SELECT * FROM p_online WHERE online_id='1'");
$rs_online = mysql_fetch_array($sql_online);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <style>
            .button{
                padding: 5px;
                text-align: right;
            }
            .submit{
                height: 20px;
                width: 70px;
            }
            .head {
                text-align: center;
                font-weight: bold;
                font-size: 14px;
            }
            .total {
                text-align:right; 
            }
            button{
                height: 25px;
                width: 50px;
            }
            .price {
                font-size: 12px;
                font-weight: bold;
                /* height: 180px; */
                /* overflow: auto; */
                width: 200px;
                padding:10px;
                margin-left: 30px;
                margin-top: -20px;
            }
            .paid{
                height: 25px;
                width: 100px;
            }
        </style>
        <script>
            function adj() {
                var sub_total = document.getElementById("sub_total").value;
                var ts_fee = document.getElementById("ts_fee").value;
                var grand_total = Number(sub_total - ts_fee);
                var counter = 1;
                var adjustment = 0;
                while (counter <= 5) {
                    var adj = document.getElementById("adj_" + counter).value;
                    var amount = Number(document.getElementById("amount_" + counter).value);
                    if (adj == 'add') {
                        adjustment = adjustment + amount;
                        grand_total = Number(grand_total + amount);
                        document.getElementById("grand_total").value = grand_total;
                    }
                    if (adj == 'deduct') {
                        adjustment = adjustment - amount;
                        grand_total = Number(grand_total - amount);
                        document.getElementById("grand_total").value = grand_total;
                    }
                    counter++;
                }
                document.getElementById("adjustment").value = adjustment;
            }
        </script>
        <script>
            function enter() {
                var cheque_name = $("#cheque_name").val();
                var cheque_number = $("#cheque_number").val();
                var voucher_number = $("#voucher_number").val();
                var sub_total = $("#sub_total").val();
                var ts_fee = $("#ts_fee").val();
                var adjustment = $("#adjustment").val();
                var grand_total = $("#grand_total").val();
                var adj_1 = $("#adj_1").val();
                var desc_1 = $("#desc_1").val();
                var amount_1 = $("#amount_1").val();
                var adj_2 = $("#adj_2").val();
                var desc_2 = $("#desc_2").val();
                var amount_2 = $("#amount_2").val();
                var adj_3 = $("#adj_3").val();
                var desc_3 = $("#desc_3").val();
                var amount_3 = $("#amount_3").val();
                var adj_4 = $("#adj_4").val();
                var desc_4 = $("#desc_4").val();
                var amount_4 = $("#amount_4").val();
                var adj_5 = $("#adj_5").val();
                var desc_5 = $("#desc_5").val();
                var amount_5 = $("#amount_5").val();

                var dataString = 'cheque_name=' + cheque_name + '&cheque_number=' + cheque_number + '&voucher_number=' + voucher_number + '&sub_total=' + sub_total + '&ts_fee=' + ts_fee + '&adjustment=' + adjustment + '&grand_total=' + grand_total + '&adj_1=' + adj_1 + '&desc_1=' + desc_1 + '&amount_1=' + amount_1 + '&adj_2=' + adj_2 + '&desc_2=' + desc_2 + '&amount_2=' + amount_2 + '&adj_3=' + adj_3 + '&desc_3=' + desc_3 + '&amount_3=' + amount_3 + '&adj_4=' + adj_4 + '&desc_4=' + desc_4 + '&amount_4=' + amount_4 + '&adj_5=' + adj_5 + '&desc_5=' + desc_5 + '&amount_5=' + amount_5;

                $.ajax({
                    type: "POST",
                    url: "submit_payment.php",
                    data: dataString,
                    cache: false
                });

            }
<?php
if ($rs_online['online'] == 'on') {
    ?>
                function OnSubmitForm() {
                    if (document.myform.operation[0].checked == true)
                    {
                        document.myform.action = "payment_next.php";
                    }
                    else
                    if (document.myform.operation[1].checked == true)
                    {
                        document.myform.action = "payment_next2.php";
                    }
                    return true;
                }
    <?php
} else {
    ?>
                function OnSubmitForm() {
                    if (document.myform.operation[0].checked == true)
                    {
                        document.myform.action = "payment_next2.php";
                    }
                    else
                    if (document.myform.operation[1].checked == true)
                    {
                        document.myform.action = "payment_next.php";
                    }
                    return true;
                }
    <?php
}
?>
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
                        <div style="margin-left: -11px;" width="1200">
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
                        $sql_pending = mysql_query("SELECT * FROM scale_receiving WHERE status='pending'");
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
                            echo "<h2>PAYMENT FOR THE FOLLOWING DELIVERIES OF " . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</h2>";
                            echo "<br>";
                            echo "<table border='0'>";
                            echo "<tr>";
                            echo "<td style='vertical-align: top;'>";
                            echo "<table border='1' width='450'>";
                            echo "<tr class='head'>";
                            echo "<td width='85'>Material</td>";
                            echo "<td>Net Weight</td>";
                            echo "<td>Cost</td>";
                            echo "<td>Amount</td>";
                            echo "</tr>";
                            $ctr = 0;
                            foreach ($trans_id as $pay) {
                                if ($trans_array == "") {
                                    $trans_array = $pay;
                                    $tr = "scale_receiving.trans_id=" . $pay;
                                } else {
                                    $trans_array.="_" . $pay;
                                    $tr .= " or scale_receiving.trans_id=" . $pay;
                                }

                                $sql_trans_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id ='$pay'");
                                while ($rs_trans_details = mysql_fetch_array($sql_trans_details)) {
                                    $amount = $rs_trans_details['corrected_weight'] * $rs_trans_details['price'];
                                    mysql_query("UPDATE scale_receiving_details SET amount='$amount' WHERE detail_id='" . $rs_trans_details['detail_id'] . "'");
                                }
                                $sql_ttrans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id ='$pay'");
                                $rs_ttrans = mysql_fetch_array($sql_ttrans);
                                $sql_count = mysql_query("SELECT count(trans_id) FROM scale_receiving_details WHERE trans_id='$pay'");
                                $rs_count = mysql_fetch_array($sql_count);
                                $sql_fee = mysql_query("SELECT * FROM ts_fee WHERE fee_id='" . $rs_ttrans['ts_fee'] . "'");
                                $rs_fee = mysql_fetch_array($sql_fee);
                                $ctr = $rs_count['count(trans_id)'];
                                $count = $ctr - 1;
                                $fee_discount = $rs_fee['price'] / 2;
                                $ts_fee_discount = $fee_discount * $count;
                                $ts_fee+= $rs_fee['price'] + $ts_fee_discount;
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
                            echo "<br>";
                            echo "<table border='1' width='450'>";
                            echo "<tr class='head'>";
                            echo "<td width='80'>Adjustments</td>";
                            echo "<td>Description</td>";
                            echo "<td>Amount</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><select id='adj_1' name='adj1' onclick='adj();'>
                                    <option value=''></option>
                                    <option value='add'>ADD</option>
                                    <option value='deduct'>DEDUCT</option>
                                    </select></td>";
                            echo "<td><input id='desc_1' type='text' name='desc1' value='' size='30'></td>";
                            echo "<td><input id='amount_1' type='text' name='amount1' value='' size='9' onkeyup='adj();'></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><select id='adj_2' name='adj2' onclick='adj();'>
                                    <option value=''></option>
                                    <option value='add'>ADD</option>
                                    <option value='deduct'>DEDUCT</option>
                                    </select></td>";
                            echo "<td><input id='desc_2' type='text' name='desc_2' value='' size='30'></td>";
                            echo "<td><input id='amount_2' type='text' name='amount2' value='' size='9' onkeyup='adj();'></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><select id='adj_3' name='adj3' onclick='adj();'>
                                    <option value=''></option>
                                    <option value='add'>ADD</option>
                                    <option value='deduct'>DEDUCT</option>
                                    </select></td>";
                            echo "<td><input id='desc_3' type='text' name='desc3' value='' size='30'></td>";
                            echo "<td><input id='amount_3' type='text' name='amount3' value='' size='9' onkeyup='adj();'></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><select id='adj_4' name='adj4' onclick='adj();'>
                                    <option value=''></option>
                                    <option value='add'>ADD</option>
                                    <option value='deduct'>DEDUCT</option>
                                    </select></td>";
                            echo "<td><input id='desc_4' type='text' name='desc4' value='' size='30'></td>";
                            echo "<td><input id='amount_4' type='text' name='amount4' value='' size='9' onkeyup='adj();'></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><select id='adj_5' name='adj5' onclick='adj();'>
                                    <option value=''></option>
                                    <option value='add'>ADD</option>
                                    <option value='deduct'>DEDUCT</option>
                                    </select></td>";
                            echo "<td><input id='desc_5' type='text' name='desc5' value='' size='30'></td>";
                            echo "<td><input id='amount_5' type='text' name='amount5' value='' size='9' onkeyup='adj();'></td>";
                            echo "</tr>";
                            echo "</table>";


                            echo "<br>";
                            echo "<table border='0' width='450'>";
                            echo "<tr>";
//                            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                            echo "<td>";
                            ?>
                            <a href="mark_as_paid.php?trans_id=<?php echo $trans_array; ?>" onclick="return confirm('Are you sure you want to mark as paid? When you click ok you cant undo this action. Thanks!')"><button class='paid'>Mark as Paid</button></a>
                            <?php
//                            echo "<form action='print_voucher.php' method='POST' target='_blank'>";
//                            echo "<input type='hidden' name='trans_id' value='$trans_array'>";
//                            echo "<button onclick='enter();'>Next</button>";
//                            echo "</form>";
                            echo "</td>";

//                            
                            echo "<form name='myform' onsubmit='return OnSubmitForm();' method='POST'>";
                            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";


                            if ($rs_online['online'] == 'on') {
                                echo '<input type="radio" name="operation" value="1" checked> <font size="3">Cheque</font>';
                                echo '<input type="radio" name="operation" value="2"> <font size="3">Digibanker</font>';
                            } else {
                                echo '<input type="radio" name="operation" value="2" checked> <font size="3">Digibanker</font>';
                                echo '<input type="radio" name="operation" value="1"> <font size="3">Cheque</font>';
                            }
                            echo "</td>";
                            echo "<td align='right'>";
                            echo "<input type='hidden' name='supplier_id' value='$unique[0]'>";
                            echo "<input type='hidden' name='sup_id' value='" . $rs_sup['id'] . "'>";
//                            echo "<input type='hidden' name='voucher_no' value='$voucher_date-$voucher_number'>";
                            echo "<input type='hidden' name='sup_name' value='" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "'>";
                            echo "<input type='hidden' name='trans_id' value='$trans_array'>";
                            echo "<button onclick='enter();'>Next</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";

                            echo "</td>";
                            echo "<td style='vertical-align: top;'>";
                            echo "<table>";
//                            echo "<tr>";
//                            echo "<td class='head'>Voucher No: </td>";
//                            echo "</tr>";
//                            echo "<tr>";
//                            echo "<td><input id='voucher_number' class='total' type='text' name='voucher_number' value='$voucher_date-$voucher_number' size='8' readonly></td>";
//                            echo "</tr>";
                            echo "<tr>";
                            echo "<td class='head'>Sub Total: </td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><input id='sub_total' class='total' type='text' name='sub_total' value='" . round($sub_total, 2) . "' size='8' readonly></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td class='head'>Weighing Fee: </td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><input id='ts_fee' class='total' type='text' name='ts_fee' value='" . round($ts_fee, 2) . "' size='8' readonly></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td class='head'>Adjustments: </td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><input id='adjustment' class='total' type='text' name='adj' value='' size='8' readonly></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td class='head'>Grand Total: </td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><input id='grand_total' class='total' type='text' name='grand_total' value='" . round($sub_total - $ts_fee, 2) . "' size='8' readonly></td>";
                            echo "</tr>";
                            echo "</table>";
                            echo "<td>";
                            echo "<div class='price'>";
                            echo "<table>";
                            echo "<tr>";
                            echo "<td align='center' class='info'>Material</td>";
                            echo "<td align='center' class='info'>Price</td>";
                            echo "</tr>";
                            $ctr = 0;
                            $sql_mat_price = mysql_query("SELECT * FROM material WHERE status!='deleted' and code!='OTHERS'");
                            while ($rs_mat_price = mysql_fetch_array($sql_mat_price)) {
                                $sql_sup_price = mysql_query("SELECT * FROM suppliers_price WHERE material_id='" . $rs_mat_price['material_id'] . "' and supplier_id='" . $rs_trans['supplier_id'] . "' ORDER BY id DESC");
                                $rs_sup_price_count = mysql_num_rows($sql_sup_price);
                                $rs_sup_price = mysql_fetch_array($sql_sup_price);
                                $sql_def_price = mysql_query("SELECT * FROM default_price WHERE material_id='" . $rs_mat_price['material_id'] . "'");
                                $rs_def_price = mysql_fetch_array($sql_def_price);
                                echo "<tr>";
                                echo "<td>" . $rs_mat_price['code'] . "</td>";
                                if ($rs_sup_price_count == 0) {
                                    echo "<td>" . $rs_def_price['price'] . "</td>";
                                    echo "<td></td>";
                                } else {
                                    echo "<td>" . $rs_sup_price['price'] . "</td>";
                                    echo "<td></td>";
                                }
                                echo "</tr>";
                                $ctr++;
                            }

                            echo "</tr>";

                            echo "</table>";
                            echo "</div>";
                            echo "</td>";
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

                    </main><!-- .content -->
                </div><!-- .container-->

                <aside class="left-sidebar">
                    <iframe src="template/pending2.php" width="367" height="600" scrolling="yes"></iframe>
                </aside><!-- .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>