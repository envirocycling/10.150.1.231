<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';

function name($user_id) {
    $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='$user_id'");
    $rs_sig = mysql_fetch_array($sql_sig);

    $name = $rs_sig['initial'] . "" . substr($rs_sig['lastname'], 1);
    return $name;
}

//echo $_POST['trans_array'];

$trans_data = preg_split("[_]", $_POST['trans_id']);
$err = 0;

//foreach ($trans_data as $trans_id) {
    //$sql_check = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='$trans_id'");
    //$rs_check = mysql_fetch_array($sql_check);

    //if ($rs_check['edit_price'] == '1') {
    //    $err++;
    //}
//}

//if ($err > 0) {
//    echo "<script>";
//    echo "alert('Failed to proccess this transaction, some details are not accurate maybe other users edited some receiving of this supplier.');";
//    echo "location.replace('index.php');";
//    echo "</script>";
//}

$sql = mysql_query("SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
$rs = mysql_fetch_array($sql);

if ($rs['status'] == 'saved') {
    echo "<script>";
    echo "alert('No payment transaction generated.');";
    echo "location.replace('clear_temp.php');";
    echo "</script>";
}
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
            .table {
                font-weight: bold;
                font-size: 15px;
            }
        </style>
        <script>

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

                        echo "<input id='payment_id' type='hidden' name='payment_id' value=''>";

                        echo "<input id='supplier_id' type='hidden' name='supplier_id' value='" . $_POST['supplier_id'] . "'>";

                        echo "<h2>CHEQUE PAYMENT FOR THE DELIVERIES OF " . $_POST['sup_name'] . "</h2>";
                        echo "<br>";
                        echo "<table class='table' border='0'>";
                        echo "<tr>";
                        echo "<td>Payee Name: </td>";
                        echo "<td colspan='3'>";
                        echo "<select id='cheque_name' class='medium-input-4' name='cheque_name' required>";
                        echo "<option value=''></option>";
                        $sql_cheque = mysql_query("SELECT * FROM cheque_name WHERE supplier_id='" . $_POST['sup_id'] . "'");
                        while ($rs_cheque = mysql_fetch_array($sql_cheque)) {
                            echo "<option value='" . $rs_cheque['name'] . "'>" . $rs_cheque['name'] . "</option>";
                        }
                        echo "</select>";
                        echo "</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>If New: </td>";
                        echo "<td colspan='3'><input id='cheque_name_new' class='medium-input-4' type='text name='cheque_name_new' value=''></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>Select Account: </td>";
                        echo "<td>";
                        echo "<select id='bank_code' class='medium-select-2' name='bank_code' onchange='change(this.value);' required>";
                        echo "<option value=''></option>";
                        $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE status!='deleted'");
                        while ($rs_bank = mysql_fetch_array($sql_bank)) {
                            echo "<option value='" . $rs_bank['bank_code'] . "'>" . $rs_bank['bank_code'] . " - " . $rs_bank['location'] . "</option>";
                        }
                        echo "</select>";
                        echo "</td>";
                        echo "<td>Cheque Date:</td>";
                        echo "<td><input class='tcal' id='cheque_date' type='text name='cheque_date' value='" . date("Y/m/d") . "' readonly></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>Cheque No: </td>";
                        echo "<td><select id='cheque_no' class='medium-select-2' name='cheque_no'></select></td>";
                        echo "<td>AP:</td>";
                        echo "<td><input type='text' name='user_id' class='medium-select-2'  value='" . name($_SESSION['user_id']) . "' readonly></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>Voucher No: </td>";
                        echo "<td><input id='voucher_no' class='medium-select-2' type='text' name='voucher_no' value='' readonly></td>";
                        echo "<td>Verifier: </td>";
                        echo "<td><input id='verifier' class='medium-select-2'  type='text name='verifier' value='" . name($_SESSION['trade_verifier']) . "' readonly></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td>Signatory: </td>";
                        echo "<td><input id='signatory' class='medium-select-2'  type='text name='signatory' value='" . name($_SESSION['trade_signatory']) . "' readonly></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td colspan='4'><br>
                        &nbsp;&nbsp;&nbsp;";
                        echo "<table border='0'>";
                        echo "<tr>";
                        echo "<td>";
                        echo "<button id='save' onclick='save();' class='large-submit'>Save</button> ";
                        echo " <button id='print_details' class='large-submit' onclick='print_voucher(this.id);'>Details</button> ";
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
                    <iframe src="template/pending2.php" width="367" height="600" scrolling="yes"></iframe>
                </aside><!-- .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
