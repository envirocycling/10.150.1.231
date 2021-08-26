<?php
session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';

function voucherNo() {
    $voucher_date = date("md");
    $date = date("Y/m/d");
    $sql_voucher = mysql_query("SELECT count(voucher_no),max(voucher_no) FROM payment WHERE date='$date' and bank_code!='SBC' and status!='deleted'");
    $rs_voucher = mysql_fetch_array($sql_voucher);
    if ($rs_voucher['count(voucher_no)'] == '0') {
        $voucher_number = "01";
    } else {
        $details = preg_split("[-]", $rs_voucher['max(voucher_no)']);
        $voucher_number = $details[1] + 1;
        if ($voucher_number < 10) {
            $voucher_number = "0" . $voucher_number;
        }
    }
    $voucher_no = $voucher_date . "-" . $voucher_number;
    return $voucher_no;
}

function name($user_id) {
    $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='$user_id'");
    $rs_sig = mysql_fetch_array($sql_sig);

    $name = $rs_sig['initial'] . "" . substr($rs_sig['lastname'], 1);
    return $name;
}

$sql = mysql_query("SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
$rs = mysql_fetch_array($sql);

if ($rs['status'] == 'saved') {
    echo "<script>";
    echo "alert('No payment transaction generated.');";
    echo "location.replace('clear_temp.php');";
    echo "</script>";
}

$sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $_POST['payment_id'] . "'");
$rs_pay = mysql_fetch_array($sql_pay);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link rel="stylesheet" type="text/css" href="css/pay_form.css" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="js/payment/payReceivingNext.js"></script>
        <style>
            table{
                font-size: 15px;
                font-weight: bold;
            }
            .table {
                font-weight: bold;
                font-size: 15px;
            }
            .check{
                position: absolute;
                margin-left: -35px;
            }
            #new_cheque{
                padding: 10px;
                height: 20px;
                width: 20px;
            }
        </style>
        <script>
            $(document).ready(function () {
                var date_now = "<?php echo date("Y/m/d"); ?>";
                var date_plus8d = "<?php echo date("Y/m/d", strtotime("+8 days", strtotime($rs_pay['date']))); ?>";
                if (date_now > date_plus8d) {
                    $("input").prop("disabled", true);
                    $("textarea").prop("disabled", true);
                    $(".submit").prop("disabled", true);
                    $("select").prop("disabled", true);
                    $("#save").prop("disabled", true);
                    $("#err").html("<font color='red'>You can't edit this transaction.</font>");
                }
            });

            $.payment_id = <?php echo $_POST['payment_id']; ?>;
            $.charge_to = '<?php echo $_POST['charge_to']; ?>';
            $.remarks = '<?php echo $_POST['remarks']; ?>';

            $.cheque_date = "<?php echo $rs_pay['cheque_date']; ?>";
            $.date_now = "<?php echo date("Y/m/d"); ?>";
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
                        $details = $_POST['trans_id'];
                        $que = preg_split("[_]", $details);

//                        echo "<input id='click' type='hidden' name='click' value='0'>";
//                        echo "<input id='checker' type='hidden' name='checker' value='0'>";
//                        echo "<input type='hidden' id='save' name='save' value='0'>";
                        echo "<input id='trans_array' type='hidden' name='tras_array' value='$details'>";
                        echo "<input id='payment_id' type='hidden' name='payment_id' value='" . $_POST['payment_id'] . "'>";
                        echo "<input id='supplier_id' type='hidden' name='supplier_id' value='" . $_POST['supplier_id'] . "'>";

                        echo "<h2>PAYMENT FOR THE DELIVERIES OF " . $_POST['sup_name'] . "</h2>";
                        echo "<br>";
                        echo "<table class='table' border='0'>";
                        echo "<td>Payee Name:</td>";
                        echo "<td colspan='3'>";
                        echo "<select id='cheque_name' class='medium-input-4' name='cheque_name' required>";
                        echo "<option value='" . $rs_pay['cheque_name'] . "'>" . $rs_pay['cheque_name'] . "</opttion>";
                        $sql_cheque = mysql_query("SELECT * FROM cheque_name WHERE supplier_id='" . $_POST['sup_id'] . "' and name!='" . $rs_pay['cheque_name'] . "'");
                        while ($rs_cheque = mysql_fetch_array($sql_cheque)) {
                            echo "<option value='" . $rs_cheque['name'] . "'>" . $rs_cheque['name'] . "</option>";
                        }
                        echo "</select></td>";
                        echo "</tr>";
                        echo "<td>If New</td>";
                        echo "<td colspan='3'><input id='cheque_name_new' class='medium-input-4' type='text' name='cheque_name_new' value=''></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>Account: </td>";
                        echo "<td><input id='old_bank_code' class='medium-select-2' type='text' name='old_bank_code' value='" . $rs_pay['bank_code'] . "' readonly></td>";
                        echo "<td>Cheque Date:</td>";
                        echo "<td><input class='tcal' id='cheque_date' type='text' name='cheque_date' value='" . $rs_pay['cheque_date'] . "' readonly></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>Voucher No.: </td>";
                        echo "<td><input id='old_voucher_no' class='medium-input-3' type='text' name='voucher_no' value='" . $rs_pay['voucher_no'] . "' readonly></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>Cheque No: </td>";
                        echo "<td><input id='old_cheque_no' class='medium-input-3' type='text' name='cheque_no' value='" . $rs_pay['cheque_no'] . "' readonly></td>";
                        echo "<td>AP: </td>";
                        echo "<td><input class='medium-input-3' type='text' name='user_id' value='" . name($_SESSION['user_id']) . "' readonly></td>";
                        echo "</tr>";

                        $bc = $rs_pay['bank_code'];

                        echo "<tr>";
                        echo "<td>Select Account: </td>";
                        echo "<td><select id='bank_code' class='medium-input-3' name='bank_code' onchange='change(this.value);' required disabled>";
                        $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code = '$bc' AND status!='deleted'");
                        while ($rs_bank = mysql_fetch_array($sql_bank)) {
                            echo "<option value='" . $rs_bank['bank_code'] . "'>" . $rs_bank['bank_code'] . " - " . $rs_bank['location'] . "</option>";
                        }
                        echo "</select></td>";
                        echo "<td>Verifier: </td>";
                        
                        echo "<td><input id='verifier' class='medium-input-3' type='text name='verifier' value='" . name($_SESSION['trade_verifier']) . "' readonly></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>New Voucher No: </td>";
                        echo "<td><input id='voucher_no' class='medium-input-3' type='text' name='voucher_no' value='" . voucherNo() . "' readonly disabled></td>";
                        echo "<td>Signatory: </td>";
                        
                        echo "<td><input id='signatory' class='medium-input-3' type='text name='signatory' value='" . name($_SESSION['trade_signatory']) . "' readonly></td>";
                        echo "</tr>";


                        echo "<tr>";
                        echo "<td>New Cheque No: </td>";
                        echo "<td><select id='cheque_no' class='medium-input-3' name='cheque_no' disabled></select></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "</tr>";
                        echo "<tr>";

                        echo "<td>New Cheque: </td>";
                        echo "<td><input id='new_cheque' type='checkbox'></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td colspan='4'><br>
                        </td>";
                        echo "<table>";
                        echo "<tr>";
                        echo "<td>";
                        echo "<button id='save' onclick='save();' class='large-submit'>Save</button> ";
                        echo "<button id='print_details' class='large-submit' onclick='print_voucher(this.id);'>Details</button> ";
                        echo " <button id='print_voucher' class='large-submit' onclick='print_voucher(this.id);'>Voucher</button> ";
                        echo " <button id='print_cheque' class='large-submit' onclick='print_cheque();'>Cheque</button> ";
                        echo " <a href='clear_temp.php'><button id='finish' class='large-submit'>Finish</button></a>";
                        echo "</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><div id='msg'></div><div id='err'></div></td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        ?>
                    </main><!-- .content -->
                </div><!-- .container-->

                <aside class="left-sidebar">
                    <div id="div1"><iframe src="template/pending2.php" width="367" height="600" scrolling="yes"></iframe></div>
                </aside><!-- .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
