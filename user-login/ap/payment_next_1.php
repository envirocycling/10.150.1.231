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
$voucher_date = date("md");
$date = date("Y/m/d");
$sql_voucher = mysql_query("SELECT count(voucher_no),max(voucher_no) FROM scale_receiving WHERE voucher_date='$date'");
$rs_voucher = mysql_fetch_array($sql_voucher);
echo $rs_voucher['max(voucher_no)'];
if ($rs_voucher['count(voucher_no)'] == '0') {
    $voucher_number = "01";
} else {
    $details = preg_split("[-]", $rs_voucher['max(voucher_no)']);
    $voucher_number = $details[1] + 1;
    if ($voucher_number < 10) {
        $voucher_number = "0" . $voucher_number;
    }
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
                var account = $("#account").val();
                var verifier = $("#verifier").val();
                var cheque_no = $("#cheque_no").val();
                var cheque_name = $("#cheque_name").val();
                var cheque_name_new = $("#cheque_name_new").val();
                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var click = $("#click").val();
                if (account == '') {
                    alert('Please Choose Account.');
                } else if (verifier == '') {
                    alert('Please Choose Verifier.');
                } else if (cheque_no == '') {
                    alert('Please Input Cheque Number.');
                } else if (cheque_no == 'Range Error') {
                    alert('Please Input New Cheque Range.');
                } else if (cheque_name == '' && cheque_name_new == '') {
                    alert('Please Input Name Appear on Cheque.');
                } else {
                    if (cheque_name == '') {
                        var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name_new + '&account=' + account + '&supplier_id=' + supplier_id;
                    } else {
                        var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name + '&account=' + account + '&supplier_id=' + supplier_id;
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
                var account = $("#account").val();
                var verifier = $("#verifier").val();
                var cheque_no = $("#cheque_no").val();
                var cheque_name = $("#cheque_name").val();
                var cheque_name_new = $("#cheque_name_new").val();
                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var click = $("#click").val();
                if (account == '') {
                    alert('Please Choose Account.');
                } else if (verifier == '') {
                    alert('Please Choose Verifier.');
                } else if (cheque_no == '') {
                    alert('Please Input Cheque Number.');
                } else if (cheque_no == 'Range Error') {
                    alert('Please Input New Cheque Range.');
                } else if (cheque_name == '' && cheque_name_new == '') {
                    alert('Please Input Name Appear on Cheque.');
                } else {
                    document.getElementById("checker").value = 1;
                    if (cheque_name == '') {
                        var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name_new + '&account=' + account + '&supplier_id=' + supplier_id;
                    } else {
                        var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name + '&account=' + account + '&supplier_id=' + supplier_id;
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
            function print_cheque() {
                var account = $("#account").val();
                var verifier = $("#verifier").val();
                var cheque_no = $("#cheque_no").val();
                var cheque_name = $("#cheque_name").val();
                var cheque_name_new = $("#cheque_name_new").val();
                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var checker = $("#checker").val();
                if (account == '') {
                    alert('Please Choose Account.');
                } else if (verifier == '') {
                    alert('Please Choose Verifier.');
                } else if (cheque_no == '') {
                    alert('Please Input Cheque Number.');
                } else if (cheque_name == '' && cheque_name_new == '') {
                    alert('Please Input Name Appear on Cheque.');
                } else {
                    if (checker == 0) {
                        alert('Please Print the voucher first.');
                    } else {
                        if (cheque_name == '') {
                            var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name_new + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id;
                        } else {
                            var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id;
                        }
                        $.ajax({
                            type: "POST",
                            url: "submit_payment3.php",
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
                        window.open("print_cheque.php?trans_id=" + tras_array, 'mywindow', 'width=1200,height=600,left=50,top=50');
                    }
                }
            }

            function save_online() {
                var account = $("#account").val();
                var verifier = $("#verifier").val();
                var cheque_no = $("#cheque_no").val();
                var cheque_name = $("#cheque_name").val();
                var cheque_name_new = $("#cheque_name_new").val();
                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var checker = $("#checker").val();
                if (account == '') {
                    alert('Please Choose Account.');
                } else if (verifier == '') {
                    alert('Please Choose Verifier.');
                } else if (cheque_no == '') {
                    alert('Please Input Cheque Number.');
                } else if (cheque_name == '' && cheque_name_new == '') {
                    alert('Please Input Name Appear on Cheque.');
                } else {
                    if (checker == 0) {
                        alert('Please Print the voucher first.');
                    } else {
                        if (cheque_name == '') {
                            var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name_new + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id;
                        } else {
                            var dataString = 'cheque_no=' + cheque_no + '&cheque_name=' + cheque_name + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id;
                        }
                        $.ajax({
                            type: "POST",
                            url: "submit_payment3.php",
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
            function change(val) {
<?php
$sql_b = mysql_query("SELECT * FROM bank_accounts");
while ($rs_b = mysql_fetch_array($sql_b)) {
    $sql_che = mysql_query("SELECT max(cheque_no) FROM payment WHERE bank_code='" . $rs_b['bank_code'] . "' and cheque_status!='issued'");
    $rs_che = mysql_fetch_array($sql_che);
    $sql_c_r = mysql_query("SELECT * FROM cheque_range WHERE bank_code='" . $rs_b['bank_code'] . "' and status=''");
    $rs_c_r_c = mysql_num_rows($sql_c_r);
    $rs_c_r = mysql_fetch_array($sql_c_r);
    ?>
                    if (val == '<?php echo $rs_b['bank_code']; ?>') {
                        var value = '<?php
    if ($rs_c_r_c == 0) {
        echo "Range Error";
    } else {
        if ($rs_che['max(cheque_no)'] < $rs_c_r['from']) {
            echo sprintf("%010s", $rs_c_r['from']);
        } else if ($rs_che['max(cheque_no)'] > $rs_c_r['to']) {
            echo "Range Error";
        } else {
            echo sprintf("%010s", $rs_che['max(cheque_no)'] + 1);
        }
    }
    ?>';
                        document.getElementById("cheque_no").value = value;
                    }
    <?php
}
?>
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

                        echo "<h2>PAYMENT FOR THE DELIVERIES OF " . $_POST['sup_name'] . "</h2>";
                        echo "<br>";
                        echo "<table class='table' border='0'>";
                        echo "<tr height='30'>";
                        echo "<td>Select Account: </td>";
                        echo "<td><select id='account' name='account' onchange='change(this.value);' required>";
                        echo "<option value=''></option>";
                        $sql_bank = mysql_query("SELECT * FROM bank_accounts");
                        while ($rs_bank = mysql_fetch_array($sql_bank)) {
                            echo "<option value='" . $rs_bank['bank_code'] . "'>" . $rs_bank['bank_code'] . " - " . $rs_bank['location'] . "</option>";
                        }
                        echo "</select></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Cheque No: </td>";
                        echo "<td><input id='cheque_no' type='text' name='cheque_no' value=''></td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>AP:</td>";
                        echo "<td><input type='hidden' name='user_id' value='" . $_SESSION['user_id'] . "'>"
                        . "<input type='text' name='user_id' value='" . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . "' readonly></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Voucher No: </td>";
                        echo "<td><input type='text' name='voucher_no' value='" . $_POST['voucher_no'] . "'></td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>Verifier: </td>";
                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['verifier'] . "'");
                        $rs_sig = mysql_fetch_array($sql_sig);
                        echo "<td><input id='verifier' type='text name='verifier' value='" . $rs_sig['firstname'] . " " . $rs_sig['lastname'] . "' readonly></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Name Appear: </td>";
                        echo "<td><select id='cheque_name' name='cheque_name' required>";
                        echo "<option value=''></option>";
                        $sql_cheque = mysql_query("SELECT * FROM cheque_name WHERE supplier_id='" . $_POST['sup_id'] . "'");
                        while ($rs_cheque = mysql_fetch_array($sql_cheque)) {
                            echo "<option value='" . $rs_cheque['name'] . "'>" . $rs_cheque['name'] . "</option>";
                        }
                        echo "</td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>Signatory: </td>";
                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['signatory'] . "'");
                        $rs_sig = mysql_fetch_array($sql_sig);
                        echo "<td><input id='signatory' type='text name='signatory' value='" . $rs_sig['firstname'] . " " . $rs_sig['lastname'] . "' readonly></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;If New: </td>";
                        echo "<td><input id='cheque_name_new' type='text name='cheque_name_new' value=''></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='4'><br>
                        &nbsp;&nbsp;&nbsp;";
                        echo "<table>";
                        echo "<tr>";
                        if (!empty($que[1])) {
                            echo "<td><button onclick='print_details();'>Print Details</button>&nbsp;</td>";
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