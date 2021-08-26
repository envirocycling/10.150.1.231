<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}
if (!isset($_SESSION['verifier'])) {
    echo "<script>
    alert('Verifier is not set Please go to settings and update the setup.');
        history.back();

    </script>";
}
if (!isset($_SESSION['signatory'])) {
    echo "<script>
    alert('Signatory is not set Please go to settings and update the setup.');
    history.back();
    </script>";
}

$date = date("Y/m/d");

$sql_voucher = mysql_query("SELECT MAX( CONVERT( SUBSTRING_INDEX( voucher_no,  '-' , -1 ) , UNSIGNED INTEGER ) ) as max FROM payment WHERE bank_code =  'SBC' and status!='deleted'");
$rs_voucher = mysql_fetch_array($sql_voucher);
$voucher_no = $rs_voucher['max'] + 1;
if ($voucher_no < 10) {
    $voucher_no = "0" . $voucher_no;
}
$sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
$rs_code = mysql_fetch_array($sql_code);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link rel="stylesheet" type="text/css" href="css/pay_form.css" />
        <style>
            .table {
                font-weight: bold;
                font-size: 15px;
            }
        </style>
        <script>
            function print_details() {
                var account = 'SBC';
                var verifier = $("#verifier").val();
                var cheque_no = 'SBC - Digibanker';
                var voucher_no = $("#voucher_no").val();
                var val = $("#acct_name").val();
                var splits = val.split("_");

                var cheque_name_new = $("#acct_name_new").val();
                var acct_num_new = $("#acct_num_new").val();

                if (cheque_name_new === '') {
                    var cheque_name = escape(splits[0]);
                    var acct_num = $("#acct_num").val();
                } else {
                    var cheque_name = escape(cheque_name_new);
                    var acct_num = $("#acct_num_new").val();
                }
                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var click = $("#click").val();
                if (verifier === '') {
                    alert('Please Choose Verifier.');
                } else if (cheque_name === '' && cheque_name_new === '') {
                    alert('Please Input Account Name.');
                } else if (acct_num === '' && acct_num_new === '') {
                    alert('Please Input New Account Number.');
                } else {

                    var dataString = 'cheque_no=' + cheque_no + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id + '&account_name=' + cheque_name + '&account_num=' + acct_num;
                    $.ajax({
                        type: "POST",
                        url: "exec/recPay_exec.php?payment=submitFinal",
                        data: dataString
                    }).done(function (msg) {
                        $("#err").html(msg);
                    });
                    if (click === 1) {
                        window.open("print_details.php?trans_id=" + tras_array, 'mywindow', 'width=1200,height=600,left=50,top=50');
                    }
                    document.getElementById("click").value = 1;

                }
            }

            function print_voucher() {
                var account = 'SBC';
                var verifier = $("#verifier").val();
                var cheque_no = 'SBC - Digibanker';
                var voucher_no = $("#voucher_no").val();
                var val = $("#acct_name").val();
                var splits = val.split("_");

                var cheque_name_new = $("#acct_name_new").val();
                var acct_num_new = $("#acct_num_new").val();

                if (cheque_name_new === '') {
                    var cheque_name = escape(splits[0]);
                    var acct_num = $("#acct_num").val();
                } else {
                    var cheque_name = escape(cheque_name_new);
                    var acct_num = $("#acct_num_new").val();
                }

                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var click = $("#click").val();

                if (verifier === '') {
                    alert('Please Choose Verifier.');
                } else if (cheque_name === '' && cheque_name_new === '') {
                    alert('Please Input Account Name.');
                } else if (cheque_name_new !== '' && acct_num_new === '') {
                    alert('Please Input New Account Number.');
                } else {
                    document.getElementById("checker").value = 1;

                    var dataString = 'cheque_no=' + cheque_no + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id + '&account_name=' + cheque_name + '&account_num=' + acct_num;
                    $.ajax({
                        type: "POST",
                        url: "exec/recPay_exec.php?payment=submitFinal",
                        data: dataString
                    }).done(function (msg) {
                        $("#err").html(msg);
                    });
                    if (click === 2) {
                        window.open("print_po_digibanker.php?trans_id=" + tras_array, 'mywindow', 'width=1200,height=600,left=50,top=50');
                    }
                    document.getElementById("click").value = 2;
                }
            }

            function save_online() {
                var account = 'SBC';
                var verifier = $("#verifier").val();
                var cheque_no = 'SBC - Digibanker';
                var voucher_no = $("#voucher_no").val();
                var val = $("#acct_name").val();
                var splits = val.split("_");

                var cheque_name_new = $("#acct_name_new").val();
                var acct_num_new = $("#acct_num_new").val();

                if (cheque_name_new === '') {
                    var cheque_name = escape(splits[0]);
                    var acct_num = $("#acct_num").val();
                } else {
                    var cheque_name = escape(cheque_name_new);
                    var acct_num = $("#acct_num_new").val();
                }

                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var payment = 'digibanker';
                if (verifier === '') {
                    alert('Please Choose Verifier.');
                } else if (cheque_name === '' && cheque_name_new === '') {
                    alert('Please Input Account Name.');
                } else if (cheque_name_new !== '' && acct_num_new === '') {
                    alert('Please Input New Account Number.');
                } else {

                    var dataString = 'cheque_no=' + cheque_no + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id + '&account_name=' + cheque_name + '&account_num=' + acct_num + '&payment=' + payment;
                    $.ajax({
                        type: "POST",
                        url: "exec/recPay_exec.php?payment=submitFinalCheque",
                        data: dataString
                    }).done(function (msg) {
                        $("#err").html(msg);
                    });

                    var finish = "<a href='clear_temp.php'><button class='large-submit'>Finish</button></a>";
                    var msg = "<font color='red'>Please click the finish button to clear the temporary data and send to online payment system.</font>";
                    document.getElementById("finish").innerHTML = finish;
                    document.getElementById("msg").innerHTML = msg;
                }
            }
            function show(val) {
                var splits = val.split("_");
                document.getElementById("acct_num").value = splits[1];
            }
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
                        $details = $_POST['trans_id'];
                        $que = preg_split("[_]", $details);
                        echo "<input id='click' type='hidden' name='click' value='0'>";
                        echo "<input id='checker' type='hidden' name='checker' value='0'>";
                        echo "<input id='tras_array' type='hidden' name='tras_array' value='$details'>";

                        echo "<input id='supplier_id' type='hidden' name='supplier_id' value='" . $_POST['supplier_id'] . "'>";

                        echo "<h2>DIGIBANKER PAYMENT FOR THE DELIVERIES OF " . $_POST['sup_name'] . "</h2>";
                        echo "<br>";
                        echo "<table class='table' border='0'>";
                        echo "<tr height='30'>";
                        echo "<td>Account Name: </td>";
                        echo "<td><select id='acct_name' class='medium-input-3' name='acct_name' onchange='show(this.value);'>";
                        echo "<option value=''></option>";
                        $sql = mysql_query("SELECT * FROM sup_bank_accounts WHERE supplier_id='" . $_POST['supplier_id'] . "'");
                        while ($rs = mysql_fetch_array($sql)) {
                            echo "<option value='" . $rs['account_name'] . "_" . $rs['account_number'] . "'>" . $rs['account_name'] . "</option>";
                        }
                        echo "</td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Voucher No: </td>";
                        echo "<td>
                        <input id='voucher_no' type='hidden' name='voucher_no' value='$voucher_no'>
                        <input id='voucher_no2' class='medium-input-3' type='text' name='voucher_no2' value='SBC_" . $rs_code['code'] . "$voucher_no' readonly></td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>Account Num: </td>";
                        echo "<td><input id='acct_num' class='medium-input-3' type='text name='acct_num' value='' readonly></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;AP:</td>";
                        echo "<td><input type='hidden' name='user_id' value='" . $_SESSION['user_id'] . "'>"
                        . "<input type='text' class='medium-input-3' name='user_id' value='" . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . "' readonly></td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>New Acct Name: </td>";
                        echo "<td><input id='acct_name_new' class='medium-input-3' type='text name='acct_name_new' value=''></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Verifier: </td>";
                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['verifier'] . "'");
                        $rs_sig = mysql_fetch_array($sql_sig);
                        echo "<td><input id='verifier' class='medium-input-3' type='text name='verifier' value='" . $rs_sig['firstname'] . " " . $rs_sig['lastname'] . "' readonly></td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>Account Num: </td>";
                        echo "<td><input id='acct_num_new' class='medium-input-3' type='text name='acct_num_new' value=''></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Signatory: </td>";
                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['signatory'] . "'");
                        $rs_sig = mysql_fetch_array($sql_sig);
                        echo "<td><input id='signatory' class='medium-input-3' type='text name='signatory' value='" . $rs_sig['firstname'] . " " . $rs_sig['lastname'] . "' readonly></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='4'><br>
                        &nbsp;&nbsp;&nbsp;";
                        echo "<table>";
                        echo "<tr>";
                        echo "<td>";
                        if (!empty($que[1])) {
                            echo "<button class='large-submit' onclick='print_details();'>Details</button>&nbsp;";
                        }

                        echo "<button class='large-submit' onclick='print_voucher();'>Voucher</button>&nbsp;";

                        echo "<button class='large-submit' onclick='save_online();' onclick=\"return confirm('Are you sure you want to enter this transaction.?')\">Save</button>";

                        echo "<div id='finish'></div>";
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