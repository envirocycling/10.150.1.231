<?php
session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';

$date = date("Y/m/d");

$sql_voucher = mysql_query("SELECT MAX( CONVERT( SUBSTRING_INDEX( voucher_no,  '-' , -1 ) , UNSIGNED INTEGER ) ) as max FROM payment WHERE bank_code =  'SBC' and status!='deleted'");
$rs_voucher = mysql_fetch_array($sql_voucher);
$voucher_no = $rs_voucher['max'] + 1;
if ($voucher_no < 10) {
    $voucher_no = "0" . $voucher_no;
}
$sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
$rs_code = mysql_fetch_array($sql_code);

function name($user_id) {
    $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='$user_id'");
    $rs_sig = mysql_fetch_array($sql_sig);

    $name = $rs_sig['initial'] . "" . substr($rs_sig['lastname'], 1);
    return $name;
}

$trans_data = preg_split("[_]", $_POST['trans_id']);
$err = 0;

// foreach ($trans_data as $trans_id) {
//    $sql_check = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='$trans_id'");
//    $rs_check = mysql_fetch_array($sql_check);

//    if ($rs_check['edit_price'] == '1') {
//        $err++;
//    }
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
                        echo "<input type='hidden' id='payment_id' name='payment_id' value=''>";
                        echo "<input type='hidden' id='bank_code' name='bank_code' value='SBC'>";
                        echo "<input type='hidden' id='cheque_date' name='cheque_date' value='" . date("Y/m/d") . "'>";
                        echo "<input id='trans_array' type='hidden' name='trans_array' value='$details'>";
                        echo "<input id='supplier_id' type='hidden' name='supplier_id' value='" . $_POST['supplier_id'] . "'>";

                        echo "<h2>DIGIBANKER PAYMENT FOR THE DELIVERIES OF " . $_POST['sup_name'] . "</h2>";
                        echo "<br>";
                        echo "<table class='table' border='0'>";

                        echo "<tr>";
                        echo "<td>Account Name: </td>";
                        echo "<td><select id='cheque_name' class='medium-select-2' name='cheque_name' onchange='show(this.value);'>";
                        echo "<option value=''></option>";
                        $sql = mysql_query("SELECT * FROM sup_bank_accounts WHERE supplier_id='" . $_POST['supplier_id'] . "'");
                        while ($rs = mysql_fetch_array($sql)) {
                            echo "<option value='" . $rs['account_name'] . "_" . $rs['account_number'] . "'>" . $rs['account_name'] . "</option>";
                        }
                        echo "</select>";
                        echo "</td>";
                        echo "<td>Voucher No: </td>";
                        echo "<td>
                        <input id='cheque_no' type='hidden' name='cheque_no' value='$voucher_no'>
                        <input id='voucher_no' type='hidden' name='voucher_no' value='$voucher_no'>
                        <input id='voucher_no2' class='medium-input-3' type='text' name='voucher_no2' value='SBC_" . $rs_code['code'] . "$voucher_no' readonly></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>Account Num: </td>";
                        echo "<td><input id='account_number' class='medium-input-3' type='text name='account_number' value='' readonly></td>";
                        echo "<td>AP:</td>";
                        echo "<td><input type='text' class='medium-input-3' name='user_id' value='" . name($_SESSION['user_id']) . "' readonly></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>New Acct Name:</td>";
                        echo "<td><input id='cheque_name_new' class='medium-input-3' type='text name='cheque_name_new' value=''></td>";
                        echo "<td>Verifier: </td>";
                        echo "<td><input id='verifier' class='medium-input-3' type='text name='verifier' value='" . name($_SESSION['trade_verifier']) . "' readonly></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>Account Num: </td>";
                        echo "<td><input id='account_number_new' class='medium-input-3' type='text name='account_number_new' value=''></td>";
                        echo "<td>Signatory:</td>";
                        echo "<td><input id='signatory' class='medium-input-3' type='text name='signatory' value='" . name($_SESSION['trade_signatory']) . "' readonly></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td colspan='4'><br>
                        &nbsp;&nbsp;&nbsp;";
                        echo "<table>";
                        echo "<tr>";
                        echo "<td>";
                        echo "<button id='save' onclick='save();' class='large-submit'>Save</button> ";
                        if (!empty($que[1])) {
                            echo "<button id='print_details' class='large-submit' onclick='print_voucher(this.id);'>Details</button> ";
                        }
                        echo " <button id='print_voucher_digi' class='large-submit' onclick='print_voucher(this.id);'>Voucher</button> ";
//                        echo " <button id='print_cheque' class='large-submit' onclick='print_cheque();'>Save</button> ";
                        echo " <a href='clear_temp.php'><button id='finish' class='large-submit'>Finish</button></a>";
                        echo "<div id='finish'></div> ";
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
