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
        location.replace('initial_settings.php');
    </script>";
}
if (!isset($_SESSION['signatory'])) {
    echo "<script>
    alert('Signatory is not set Please go to settings and update the setup.');
    location.replace('initial_settings.php');
    </script>";
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
        <style>
            .table {
                font-weight: bold;
                font-size: 15px;
            }
        </style>
        <script>
            $(document).ready(function () {
                $("#cheque_name").select2();
            });

            function reload() {
                location.reload();
            }
            function print_details() {
                var account = $("#account").val();
                var verifier = $("#verifier").val();
                var cheque_no = $("#cheque_no").val();
                var voucher_no = $("#voucher_no").val();

                var cheque_name_new = escape($("#cheque_name_new").val());
                if (cheque_name_new === '') {
                    var cheque_name = escape($("#cheque_name").val());
                } else {
                    var cheque_name = cheque_name_new;
                }

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

                    var dataString = 'cheque_no=' + cheque_no + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&account=' + account + '&supplier_id=' + supplier_id;
                    $.ajax({
                        type: "POST",
                        url: "exec/recPay_exec.php?payment=submitFinal",
                        data: dataString
                    }).done(function (msg) {
                        $("#err").html(msg);
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
                var voucher_no = $("#voucher_no").val();

                var cheque_name_new = escape($("#cheque_name_new").val());
                if (cheque_name_new === '') {
                    var cheque_name = escape($("#cheque_name").val());
                } else {
                    var cheque_name = cheque_name_new;
                }

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

                    var dataString = 'cheque_no=' + cheque_no + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&account=' + account + '&supplier_id=' + supplier_id;
                    $.ajax({
                        type: "POST",
                        url: "exec/recPay_exec.php?payment=submitFinal",
                        data: dataString
                    }).done(function (msg) {
                        $("#err").html(msg);
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
                var voucher_no = $("#voucher_no").val();
                var cheque_date = $("#cheque_date").val();

                var cheque_name_new = escape($("#cheque_name_new").val());
                if (cheque_name_new === '') {
                    var cheque_name = escape($("#cheque_name").val());
                } else {
                    var cheque_name = cheque_name_new;
                }

                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var checker = $("#checker").val();
                var payment = 'cheque';

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

                        var dataString = 'cheque_no=' + cheque_no + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id + '&payment=' + payment;
                        $.ajax({
                            type: "POST",
                            url: "exec/recPay_exec.php?payment=submitFinalCheque",
                            data: dataString
                        }).done(function (msg) {
                            $("#err").html(msg);
                        });

                        var finish = "<a href='clear_temp.php'><button class='large-submit'>Finish</button></a>";
                        var msg = "<font color='red'>Please click the finish button to clear the temporary payment data.</font>";
                        document.getElementById("finish").innerHTML = finish;
                        document.getElementById("msg").innerHTML = msg;
                        window.open("print_cheque.php?trans_id=" + tras_array + "&date=" + cheque_date, 'mywindow', 'width=1200,height=600,left=50,top=50');
                    }
                }
            }

            function change(val) {
<?php
$sql_b = mysql_query("SELECT * FROM bank_accounts");
while ($rs_b = mysql_fetch_array($sql_b)) {
    $sql_che = mysql_query("SELECT max(cheque_no) FROM payment WHERE bank_code='" . $rs_b['bank_code'] . "' and cheque_status!='issued' and status!='deleted'");
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
        } else if ($rs_che['max(cheque_no)'] >= $rs_c_r['to']) {
            echo "Range Error";
        } else {
            echo sprintf("%010s", $rs_che['max(cheque_no)'] + 1);
        }
    }
    ?>';

    <?php
    $voucher_date = date("md");
    $date = date("Y/m/d");
    $sql_voucher = mysql_query("SELECT count(voucher_no),max(voucher_no) FROM payment WHERE bank_code='" . $rs_b['bank_code'] . "' and date='$date' and status!='deleted'");
    $rs_voucher = mysql_fetch_array($sql_voucher);
    if ($rs_voucher['count(voucher_no)'] == '0' || $rs_voucher['count(voucher_no)'] == '') {
        $voucher_number = "01";
    } else {
        $details = preg_split("[-]", $rs_voucher['max(voucher_no)']);
        $voucher_number = $details[1] + 1;
        if ($voucher_number < 10) {
            $voucher_number = "0" . $voucher_number;
        }
    }
    ?>
                        var value2 = "<?php echo $voucher_date; ?>-<?php echo $voucher_number; ?>";
                                    document.getElementById("cheque_no").value = value;
                                    document.getElementById("voucher_no").value = value2;
    <?php
    $sql_tpc = mysql_query("SELECT * FROM temp_payment WHERE bank_code='" . $rs_b['bank_code'] . "' and user_id!='" . $_SESSION['user_id'] . "'");
    $rs_tpc = mysql_num_rows($sql_tpc);
    ?>
                                    var c = '<?php echo $rs_tpc; ?>';
                                    if (c >= 1) {
                                        if ($("#account").val() == '1') {
                                            document.getElementById("print_details").disabled = true;
                                        }
                                        document.getElementById("print_voucher").disabled = true;
                                        document.getElementById("print_cheque").disabled = true;

                                        var finish = "<button class='large-submit' onclick='reload();'>Refresh</button>";
                                        var msg = "<font color='red'>Other users is using this Bank Account, Please refresh this page and try again.</font>";
                                        document.getElementById("finish").innerHTML = finish;
                                        document.getElementById("msg").innerHTML = msg;
                                    } else {
                                        if ($("#account").val() == '1') {
                                            document.getElementById("print_details").disabled = false;
                                        }
                                        document.getElementById("print_voucher").disabled = false;
                                        document.getElementById("print_cheque").disabled = false;

                                        var finish = "";
                                        var msg = "";
                                        document.getElementById("finish").innerHTML = finish;
                                        document.getElementById("msg").innerHTML = msg;
                                    }

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

                        echo "<h2>CHEQUE PAYMENT FOR THE DELIVERIES OF " . $_POST['sup_name'] . "</h2>";
                        echo "<br>";
                        echo "<table class='table' border='0'>";
                        echo "<tr height='30'>";
                        echo "<td>Payee Name: </td>";
                        echo "<td colspan='3'><select id='cheque_name' class='medium-input-4' name='cheque_name' required>";
                        echo "<option value=''></option>";
                        $sql_cheque = mysql_query("SELECT * FROM cheque_name WHERE supplier_id='" . $_POST['sup_id'] . "'");
                        while ($rs_cheque = mysql_fetch_array($sql_cheque)) {
                            echo "<option value='" . $rs_cheque['name'] . "'>" . $rs_cheque['name'] . "</option>";
                        }
                        echo "</td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>If New: </td>";
                        echo "<td colspan='3'><input id='cheque_name_new' class='medium-input-4' type='text name='cheque_name_new' value=''></td>";
                        echo "</tr>";



                        echo "<tr height='30'>";
                        echo "<td>Select Account: </td>";
                        echo "<td><select id='account' class='medium-select-2' name='account' onchange='change(this.value);' required>";
                        echo "<option value=''></option>";
                        $sql_bank = mysql_query("SELECT * FROM bank_accounts");
                        while ($rs_bank = mysql_fetch_array($sql_bank)) {
                            echo "<option value='" . $rs_bank['bank_code'] . "'>" . $rs_bank['bank_code'] . " - " . $rs_bank['location'] . "</option>";
                        }
                        echo "</select></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Cheque Date:</td>";
                        echo "<td><input class='tcal' id='cheque_date' type='text name='cheque_date' value='' readonly></td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>Voucher No: </td>";
                        echo "<td><input id='voucher_no' class='medium-input-3' type='text' name='voucher_no' value='' readonly></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;AP:</td>";
                        echo "<td><input type='hidden' name='user_id' value='" . $_SESSION['user_id'] . "'>"
                        . "<input type='text' class='medium-input-3' name='user_id' value='" . strtoupper($_SESSION['initial']) . "" . strtolower(substr($_SESSION['lastname'], 1)) . "' readonly></td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>Cheque No: </td>";
                        echo "<td><input id='cheque_no' class='medium-input-3' type='text' name='cheque_no' value='' readonly></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Verifier: </td>";
                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['verifier'] . "'");
                        $rs_sig = mysql_fetch_array($sql_sig);
                        echo "<td><input id='verifier' class='medium-input-3' type='text name='verifier' value='" . strtoupper($rs_sig['initial']) . "" . strtolower(substr($rs_sig['lastname'], 1)) . "' readonly></td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Signatory: </td>";
                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['signatory'] . "'");
                        $rs_sig = mysql_fetch_array($sql_sig);
                        echo "<td><input id='signatory' class='medium-input-3' type='text name='signatory' value='" . strtoupper($rs_sig['initial']) . "" . strtolower(substr($rs_sig['lastname'], 1)) . "' readonly></td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td colspan='4'><br>
                        &nbsp;&nbsp;&nbsp;";
                        echo "<table border='0'>";
                        echo "<tr>";
                        echo "<td>";
                        if (!empty($que[1])) {
                            echo "<input id='c_1om' type='hidden' name='c_1om' value='1'><button id='print_details' class='large-submit' onclick='print_details();'>Details</button> ";
                        } else {
                            echo "<input id='c_1om' type='hidden' name='c_1om' value='0'> ";
                        }

                        echo "<button id='print_voucher' class='large-submit' onclick='print_voucher();'>Voucher</button> ";

                        echo "<button id='print_cheque' class='large-submit' onclick='print_cheque();'>Cheque</button> ";

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
                    <iframe src="template/pending2.php" width="367" height="600" scrolling="yes"></iframe>
                </aside><!-- .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>