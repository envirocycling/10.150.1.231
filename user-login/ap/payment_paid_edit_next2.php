<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}
$voucher_date = date("md");
$date = date("Y/m/d");
$sql_voucher = mysql_query("SELECT count(voucher_no),max(voucher_no) FROM payment WHERE date='$date'");
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
if (!isset($_SESSION['verifier'])) {
    echo "<script>
    alert('Verifier is not set Please go to settings and update the setup.');
        history.back();

    </script>";
}
if (!isset($_SESSION['signatory'])) {
    echo "<script>
    alert('Verifier is not set Please go to settings and update the setup.');
    history.back();
    </script>";
}
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
            .table {
                font-weight: bold;
                font-size: 15px;
            }
            button{
                height: 25px;
                width: 100px;
            }
            .submit{
                height: 20px;
                width: 70px;
            }
        </style>
        <script>
            function print_details() {
                var account = 'SBC';
                var verifier = $("#verifier").val();
                var cheque_no = 'SBC - Digibanker';
                var val = $("#acct_name").val();
                var splits = val.split("_");
                var cheque_name = splits[0];
                var cheque_name_new = $("#acct_name_new").val();
                var acct_num = $("#acct_num").val();
                var acct_num_new = $("#acct_num_new").val();
                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var click = $("#click").val();
                if (verifier == '') {
                    alert('Please Choose Verifier.');
                } else if (cheque_name == '' && cheque_name_new == '') {
                    alert('Please Input Account Name.');
                } else if (acct_num_new == '') {
                    alert('Please Input New Account Number.');
                } else {
                    if (cheque_name == '') {
                        var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name_new + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id + '&account_name=' + cheque_name_new + '&account_num=' + acct_num_new;
                    } else {
                        var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id + '&account_name=' + cheque_name + '&account_num=' + acct_num;
                    }
                    $.ajax({
                        type: "POST",
                        url: "submit_payment2.php",
                        data: dataString,
                        cache: false
                    });
                    if (click == 1) {
                        window.open("print_details.php?trans_id=" + tras_array, 'mywindow', 'width=1200,height=600,left=50,top=50');
                    }
                    document.getElementById("click").value = 1;

                }
            }

            function print_voucher() {
                var account = 'SBC';
                var verifier = $("#verifier").val();
                var cheque_no = 'SBC - Digibanker';
                var val = $("#acct_name").val();
                var splits = val.split("_");
                var cheque_name = splits[0];
                var cheque_name_new = $("#acct_name_new").val();
                var acct_num = $("#acct_num").val();
                var acct_num_new = $("#acct_num_new").val();
                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var click = $("#click").val();

                if (verifier == '') {
                    alert('Please Choose Verifier.');
                } else if (cheque_name == '' && cheque_name_new == '') {
                    alert('Please Input Account Name.');
                } else if (cheque_name_new != '' && acct_num_new == '') {
                    alert('Please Input New Account Number.');
                } else {
                    document.getElementById("checker").value = 1;
                    if (cheque_name == '') {
                        var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name_new + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id + '&account_name=' + cheque_name_new + '&account_num=' + acct_num_new;
                    } else {
                        var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id + '&account_name=' + cheque_name + '&account_num=' + acct_num;
                    }
                    $.ajax({
                        type: "POST",
                        url: "submit_payment2.php",
                        data: dataString,
                        cache: false
                    });
                    if (click == 2) {
                        window.open("print_voucher.php?trans_id=" + tras_array, 'mywindow', 'width=1200,height=600,left=50,top=50');
                    }
                    document.getElementById("click").value = 2;
                }
            }

            function save_online() {
                var account = 'SBC';
                var verifier = $("#verifier").val();
                var cheque_no = 'SBC - Digibanker';
                var val = $("#acct_name").val();
                var splits = val.split("_");
                var cheque_name = splits[0];
                var cheque_name_new = $("#acct_name_new").val();
                var acct_num = $("#acct_num").val();
                var acct_num_new = $("#acct_num_new").val();
                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var payment_id = $("#payment_id").val();
                var checker = $("#checker").val();
                if (verifier == '') {
                    alert('Please Choose Verifier.');
                } else if (cheque_name == '' && cheque_name_new == '') {
                    alert('Please Input Account Name.');
                } else if (cheque_name_new != '' && acct_num_new == '') {
                    alert('Please Input New Account Number.');
                } else {
                    if (checker == 0) {
                        alert('Please Print the voucher first.');
                    } else {
                        if (cheque_name == '') {
                            var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name_new + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id + '&account_name=' + cheque_name_new + '&account_num=' + acct_num_new + '&payment_id=' + payment_id;
                        } else {
                            var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id + '&account_name=' + cheque_name + '&account_num=' + acct_num + '&payment_id=' + payment_id;
                        }
                        $.ajax({
                            type: "POST",
                            url: "submit_payment_edit3.php",
                            data: dataString,
                            cache: false
                        });
                        $.ajax({
                            type: "POST",
                            url: "submit_payment2.php",
                            data: dataString,
                            cache: false
                        });

                        $("#div1").load("template/pending.php");
                        var finish = "<a href='paid_payments.php'><button>Finish</button></a>";
                        document.getElementById("finish").innerHTML = finish;
                        alert('The payment already save to the database.');
                        //                        window.open("print_cheque.php?trans_id=" + tras_array, 'mywindow', 'width=1200,height=600,left=50,top=50');
                    }
                }
            }
            function show(val) {
                var splits = val.split("_");
                document.getElementById("acct_num").value = splits[1];
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
                        echo "<input id='payment_id' type='hidden' name='payment_id' value='" . $_POST['payment_id'] . "'>";
                        echo "<input id='supplier_id' type='hidden' name='supplier_id' value='" . $_POST['supplier_id'] . "'>";

                        echo "<h2>PAYMENT FOR THE DELIVERIES OF " . $_POST['sup_name'] . "</h2>";
                        echo "<br>";
                        echo "<table class='table' border='0'>";
                        echo "<tr height='30'>";
                        echo "<td>Voucher No: </td>";
                        echo "<td><input type='text' name='voucher_no' value='$voucher_date-$voucher_number' readonly></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Account Name: </td>";
                        echo "<td><input id='old_acct_name' type='text' name='old_acct_name' value='" . $_POST['account_name'] . "' readonly></td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>AP:</td>";
                        echo "<td><input type='hidden' name='user_id' value='" . $_SESSION['user_id'] . "'>"
                        . "<input type='text' name='user_id' value='" . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . "' readonly></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Account Num: </td>";
                        echo "<td><input id='old_acct_num' type='text' name='old_acct_num' value='" . $_POST['account_number'] . "' readonly></td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>Verifier: </td>";
                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['verifier'] . "'");
                        $rs_sig = mysql_fetch_array($sql_sig);
                        echo "<td><input id='verifier' type='text name='verifier' value='" . $rs_sig['firstname'] . " " . $rs_sig['lastname'] . "' readonly></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;New Acct Num: </td>";
                        echo "<td><select id='acct_name' name='acct_name' onchange='show(this.value);'>";
                        echo "<option value=''></option>";
                        $sql = mysql_query("SELECT * FROM sup_bank_accounts WHERE supplier_id='" . $_POST['supplier_id'] . "'");
                        while ($rs = mysql_fetch_array($sql)) {
                            echo "<option value='" . $rs['account_name'] . "_" . $rs['account_number'] . "'>" . $rs['account_name'] . "</option>";
                        }
                        echo "</td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>Signatory: </td>";
                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['signatory'] . "'");
                        $rs_sig = mysql_fetch_array($sql_sig);
                        echo "<td><input id='signatory' type='text name='signatory' value='" . $rs_sig['firstname'] . " " . $rs_sig['lastname'] . "' readonly></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;New Acct Num: </td>";
                        echo "<td><input id='acct_num' type='text' name='acct_num' value='' readonly></td>";
                        echo "</tr>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>&nbsp;</td>";
                        echo "<td>&nbsp;</td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;If New Acct Name: </td>";
                        echo "<td><input type='text' id='acct_name_new' name='acct_name_new' value=''></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>&nbsp;</td>";
                        echo "<td>&nbsp;</td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;If New Acct Num: </td>";
                        echo "<td><input id='acct_num_new' type='text' name='acct_num_new' value=''></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='4'><br>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        </td>";
                        echo "<table>";
                        echo "<tr>";
                        if (!empty($que[1])) {
                            echo "<td><button onclick='print_details();'>Print Details</button>&nbsp;
                        </td>";
                        }

                        echo "<td>&nbsp;<button onclick='print_voucher();'>Print Voucher</button></td>";


                        $sql_online = mysql_query("SELECT * FROM p_online WHERE online_id='1'");
                        $rs_online = mysql_fetch_array($sql_online);
                        if ($rs_online['online'] == 'on') {
                            echo "<td>&nbsp;<button onclick='print_cheque();'>Print Cheque</button>&nbsp;</td>";
                        } else {
                            echo "<td>&nbsp;<button onclick='save_online();'>Save</button>&nbsp;</td>";
                        }

                        echo "<td><div id='finish'></div></td>";
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